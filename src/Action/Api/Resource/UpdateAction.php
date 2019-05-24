<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Action\Api\Resource;

use Ixocreate\Admin\Entity\User;
use Ixocreate\Admin\Resource\Action\UpdateActionAwareInterface;
use Ixocreate\Admin\Response\ApiSuccessResponse;
use Ixocreate\Application\Http\Middleware\MiddlewareSubManager;
use Ixocreate\Database\Repository\Factory\RepositorySubManager;
use Ixocreate\Database\Repository\RepositoryInterface;
use Ixocreate\Entity\EntityInterface;
use Ixocreate\Resource\ResourceInterface;
use Ixocreate\Resource\ResourceSubManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Stratigility\Middleware\CallableMiddlewareDecorator;
use Zend\Stratigility\MiddlewarePipe;

final class UpdateAction implements MiddlewareInterface
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
     * @var ResourceSubManager
     */
    private $resourceSubManager;

    public function __construct(
        RepositorySubManager $repositorySubManager,
        MiddlewareSubManager $middlewareSubManager,
        ResourceSubManager $resourceSubManager
    ) {
        $this->repositorySubManager = $repositorySubManager;
        $this->middlewareSubManager = $middlewareSubManager;
        $this->resourceSubManager = $resourceSubManager;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $resource = $this->resourceSubManager->get($request->getAttribute('resource'));

        $middlewarePipe = new MiddlewarePipe();

        if ($resource instanceof UpdateActionAwareInterface) {
            /** @var MiddlewareInterface $action */
            $action = $this->middlewareSubManager->get($resource->updateAction($request->getAttribute(User::class)));
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
        $entity = $repository->find($request->getAttribute("id"));

        $data = $request->getParsedBody();
        foreach ($data as $name => $value) {
            $entity = $entity->with($name, $value);
        }
        if ($entity::getDefinitions()->has('updatedAt')) {
            $entity = $entity->with('updatedAt', new \DateTime());
        }
        $repository->save($entity);

        return new ApiSuccessResponse();
    }
}
