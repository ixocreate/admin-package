<?php
/**
 * kiwi-suite/admin (https://github.com/kiwi-suite/admin)
 *
 * @package kiwi-suite/admin
 * @see https://github.com/kiwi-suite/admin
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace KiwiSuite\Admin\Action\Api\Config;

use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use KiwiSuite\Admin\Config\AdminConfig;
use KiwiSuite\Admin\Helper\ServerUrlHelper;
use KiwiSuite\Admin\Response\ApiSuccessResponse;
use KiwiSuite\Admin\Route\RouteConfig;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ConfigAction implements MiddlewareInterface
{
    /**
     * @var AdminConfig
     */
    private $adminConfig;

    /**
     * @var RouteConfig
     */
    private $routeConfig;

    /**
     * @var ServerUrlHelper
     */
    private $serverUrlHelper;

    /**
     * ConfigAction constructor.
     * @param AdminConfig $adminConfig
     * @param RouteConfig $routeConfig
     * @param ServerUrlHelper $serverUrlHelper
     */
    public function __construct(AdminConfig $adminConfig, RouteConfig $routeConfig, ServerUrlHelper $serverUrlHelper)
    {
        $this->adminConfig = $adminConfig;
        $this->routeConfig = $routeConfig;
        $this->serverUrlHelper = $serverUrlHelper;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return new ApiSuccessResponse([
            'routes' => $this->getRoutes(),
            'sessionDomain' => $this->adminConfig->getSessionDomain($request->getUri()->getHost()),
        ]);
    }

    /**
     * @return array
     */
    private function getRoutes(): array
    {
        $routes = [];

        // TODO Login Check / Permission Check

        foreach ($this->routeConfig->getRoutes() as $route) {
            // if (\mb_substr($route['path'], 0, 4) !== "/api") {
            //     continue;
            // }
            // dot notation to camelCase
            $routeName = \str_replace(' ', '', \ucwords(\str_replace('.', ' ', $route['name'])));
            $routeName[0] = \mb_strtolower($routeName[0]);
            $routes[$routeName] = $this->adminConfig->getUri() . $route['path'];
        }

        return $routes;
    }
}
