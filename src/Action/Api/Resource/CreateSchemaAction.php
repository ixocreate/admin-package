<?php
namespace KiwiSuite\Admin\Action\Api\Resource;

use KiwiSuite\Admin\Response\ApiDetailResponse;
use KiwiSuite\ApplicationHttp\Middleware\MiddlewareSubManager;
use KiwiSuite\Contract\Resource\AdminAwareInterface;
use KiwiSuite\Contract\Resource\ResourceInterface;
use KiwiSuite\Schema\Builder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Stratigility\Middleware\CallableMiddlewareDecorator;
use Zend\Stratigility\MiddlewarePipe;

final class CreateSchemaAction implements MiddlewareInterface
{
    /**
     * @var MiddlewareSubManager
     */
    private $middlewareSubManager;

    /**
     * @var Builder
     */
    private $builder;

    public function __construct(MiddlewareSubManager $middlewareSubManager, Builder $builder)
    {
        $this->middlewareSubManager = $middlewareSubManager;
        $this->builder = $builder;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var AdminAwareInterface $resource */
        $resource = $request->getAttribute(ResourceInterface::class);

        $middlewarePipe = new MiddlewarePipe();

        if (!empty($resource->createSchemaAction())) {
            /** @var MiddlewareInterface $action */
            $action = $this->middlewareSubManager->get($resource->createSchemaAction());
            $middlewarePipe->pipe($action);
        }

        $middlewarePipe->pipe(new CallableMiddlewareDecorator(function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($resource){
            return $this->handleRequest($resource, $request, $handler);
        }));

        return $middlewarePipe->process($request, $handler);

    }

    private function handleRequest(AdminAwareInterface $resource, ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        return new ApiDetailResponse(
            $resource,
            [],
            $resource->createSchema($this->builder),
            []
        );
    }
}
