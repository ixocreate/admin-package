<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Package\Action\Api\Resource;

use Ixocreate\Admin\Package\Entity\User;
use Ixocreate\Admin\Package\Response\ApiSuccessResponse;
use Ixocreate\Application\Http\Middleware\MiddlewareSubManager;
use Ixocreate\Admin\Resource\Action\CreateActionAwareInterface;
use Ixocreate\Resource\Package\ResourceInterface;
use Ixocreate\Database\Package\Repository\Factory\RepositorySubManager;
use Ixocreate\Database\Package\Repository\RepositoryInterface;
use Ixocreate\Entity\Package\EntityInterface;
use Ixocreate\Resource\Package\SubManager\ResourceSubManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ramsey\Uuid\Uuid;
use Zend\Stratigility\Middleware\CallableMiddlewareDecorator;
use Zend\Stratigility\MiddlewarePipe;

final class CreateAction implements MiddlewareInterface
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

        if ($resource instanceof CreateActionAwareInterface) {
            /** @var MiddlewareInterface $action */
            $action = $this->middlewareSubManager->get($resource->createAction($request->getAttribute(User::class)));
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

        $data = $request->getParsedBody();
        $data['id'] = Uuid::uuid4();

        $entity = $repository->getEntityName();
        if ($entity::getDefinitions()->has('createdAt')) {
            $data['createdAt'] = new \DateTime();
        }
        if ($entity::getDefinitions()->has('updatedAt')) {
            $data['updatedAt'] = new \DateTime();
        }
        /** @var EntityInterface $entity */
        $entity = new $entity($data);
        $repository->save($entity);

        return new ApiSuccessResponse(['id' => $data['id']]);
    }
}
