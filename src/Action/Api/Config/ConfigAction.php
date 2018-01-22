<?php
namespace KiwiSuite\Admin\Action\Api\Config;

use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use KiwiSuite\Admin\Helper\ServerUrlHelper;
use KiwiSuite\Admin\Helper\UrlHelper;
use KiwiSuite\Admin\Response\ApiSuccessResponse;
use KiwiSuite\Admin\Route\RouteConfig;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ConfigAction implements MiddlewareInterface
{
    /**
     * @var RouteConfig
     */
    private $routeConfig;

    /**
     * @var ServerUrlHelper
     */
    private $serverUrlHelper;


    public function __construct(RouteConfig $routeConfig, ServerUrlHelper $serverUrlHelper)
    {
        $this->routeConfig = $routeConfig;
        $this->serverUrlHelper = $serverUrlHelper;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return new ApiSuccessResponse([
            'routes' => $this->getRoutes()
        ]);
    }

    private function getRoutes() : array
    {
        $routes = [];

        //TODO Login Check / Permission Check

        foreach ($this->routeConfig->getRoutes() as $route) {
            if (substr($route['path'], 0, 4) !== "/api") {
                continue;
            }
            $routes[$route['name']] = $this->serverUrlHelper->generate($route['path']);
        }

        return $routes;
    }
}
