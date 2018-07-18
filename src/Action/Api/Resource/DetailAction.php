<?php
namespace KiwiSuite\Admin\Action\Api\Resource;

use KiwiSuite\Admin\Response\ApiDetailResponse;
use KiwiSuite\Admin\Response\ApiSuccessResponse;
use KiwiSuite\ApplicationHttp\Middleware\MiddlewareSubManager;
use KiwiSuite\Contract\Resource\AdminAwareInterface;
use KiwiSuite\Contract\Resource\ResourceInterface;
use KiwiSuite\Database\Repository\Factory\RepositorySubManager;
use KiwiSuite\Database\Repository\RepositoryInterface;
use KiwiSuite\Entity\Entity\EntityInterface;
use KiwiSuite\Schema\Builder;
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
     * @var Builder
     */
    private $builder;

    public function __construct(RepositorySubManager $repositorySubManager, MiddlewareSubManager $middlewareSubManager, Builder $builder)
    {

        $this->repositorySubManager = $repositorySubManager;
        $this->middlewareSubManager = $middlewareSubManager;
        $this->builder = $builder;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var AdminAwareInterface $resource */
        $resource = $request->getAttribute(ResourceInterface::class);

        $middlewarePipe = new MiddlewarePipe();

        if (!empty($resource->detailAction())) {
            /** @var MiddlewareInterface $action */
            $action = $this->middlewareSubManager->get($resource->detailAction());
            $middlewarePipe->pipe($action);
        }

        $middlewarePipe->pipe(new CallableMiddlewareDecorator(function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($resource){
            return $this->handleRequest($resource, $request, $handler);
        }));

        return $middlewarePipe->process($request, $handler);
    }

    private function handleRequest(AdminAwareInterface $resource, ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        /** @var RepositoryInterface $repository */
        $repository = $this->repositorySubManager->get($resource->repository());

        /** @var EntityInterface $entity */
        $entity = $repository->find($request->getAttribute("id"));

        return new ApiDetailResponse(
            $resource,
            $entity->toPublicArray(),
            $resource->updateSchema($this->builder),
            []
        );
    }
}
