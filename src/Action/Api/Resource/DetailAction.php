<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Action\Api\Resource;

use Ixocreate\Admin\Entity\User;
use Ixocreate\Admin\Permission\Permission;
use Ixocreate\Admin\Resource\Action\DetailActionAwareInterface;
use Ixocreate\Admin\Response\ApiDetailResponse;
use Ixocreate\Admin\Response\ApiErrorResponse;
use Ixocreate\Application\Http\Middleware\MiddlewareSubManager;
use Ixocreate\Database\Repository\Factory\RepositorySubManager;
use Ixocreate\Database\Repository\RepositoryInterface;
use Ixocreate\Entity\EntityInterface;
use Ixocreate\Resource\ResourceInterface;
use Ixocreate\Resource\ResourceSubManager;
use Ixocreate\Schema\Builder\BuilderInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Stratigility\Middleware\CallableMiddlewareDecorator;
use Zend\Stratigility\MiddlewarePipe;

final class DetailAction implements MiddlewareInterface
{
    /**
     * @var RepositorySubManager
     */
    private $repositorySubManager;

    /**
     * @var MiddlewareSubManager
     */
    private $middlewareSubManager;

    /**
     * @var BuilderInterface
     */
    private $builder;

    /**
     * @var ResourceSubManager
     */
    private $resourceSubManager;

    /**
     * DetailAction constructor.
     * @param RepositorySubManager $repositorySubManager
     * @param MiddlewareSubManager $middlewareSubManager
     * @param BuilderInterface $builder
     * @param ResourceSubManager $resourceSubManager
     */
    public function __construct(
        RepositorySubManager $repositorySubManager,
        MiddlewareSubManager $middlewareSubManager,
        BuilderInterface $builder,
        ResourceSubManager $resourceSubManager
    ) {
        $this->repositorySubManager = $repositorySubManager;
        $this->middlewareSubManager = $middlewareSubManager;
        $this->builder = $builder;
        $this->resourceSubManager = $resourceSubManager;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $resource = $this->resourceSubManager->get($request->getAttribute('resource'));

        /** @var Permission $permission */
        $permission = $request->getAttribute(Permission::class);
        if (!$permission->can('resource.' . $resource->serviceName() . '.detail')) {
            return new ApiErrorResponse('forbidden', [], 403);
        }

        $middlewarePipe = new MiddlewarePipe();

        if ($resource instanceof DetailActionAwareInterface) {
            /** @var MiddlewareInterface $action */
            $action = $this->middlewareSubManager->get($resource->detailAction($request->getAttribute(User::class)));
            $middlewarePipe->pipe($action);
        }

        $middlewarePipe->pipe(new CallableMiddlewareDecorator(function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($resource) {
            return $this->handleRequest($resource, $request, $handler);
        }));

        return $middlewarePipe->process($request, $handler);
    }

    private function handleRequest(ResourceInterface $resource, ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        /** @var RepositoryInterface $repository */
        $repository = $this->repositorySubManager->get($resource->repository());

        /** @var EntityInterface $entity */
        $entity = $repository->find($request->getAttribute('id'));

        return new ApiDetailResponse(
            $resource,
            $entity->toPublicArray(),
            []
        );
    }
}
