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

use KiwiSuite\Admin\Config\AdminConfig;
use KiwiSuite\Admin\Helper\ServerUrlHelper;
use KiwiSuite\Admin\Helper\UrlHelper;
use KiwiSuite\Admin\Pipe\PipeConfig;
use KiwiSuite\Admin\Response\ApiSuccessResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ConfigAction implements MiddlewareInterface
{
    /**
     * @var AdminConfig
     */
    private $adminConfig;

    /**
     * @var ServerUrlHelper
     */
    private $serverUrlHelper;

    /**
     * @var PipeConfig
     */
    private $pipeConfig;
    /**
     * @var UrlHelper
     */
    private $urlHelper;

    /**
     * ConfigAction constructor.
     * @param AdminConfig $adminConfig
     * @param PipeConfig $pipeConfig
     * @param ServerUrlHelper $serverUrlHelper
     */
    public function __construct(AdminConfig $adminConfig, PipeConfig $pipeConfig, ServerUrlHelper $serverUrlHelper, UrlHelper $urlHelper)
    {
        $this->adminConfig = $adminConfig;
        $this->serverUrlHelper = $serverUrlHelper;
        $this->pipeConfig = $pipeConfig;
        $this->urlHelper = $urlHelper;
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
        ]);
    }

    /**
     * @return array
     */
    private function getRoutes(): array
    {
        $routes = [];

        foreach ($this->pipeConfig->getMiddlewarePipe() as $middlewarePipe) {
            if ($middlewarePipe['type'] !== PipeConfig::TYPE_SEGMENT) {
                continue;
            }

            if ($middlewarePipe['value']['segment'] !== '/api') {
                continue;
            }

            foreach ($middlewarePipe['value']['pipeConfig']->getRoutes() as $route) {
                if (substr($route['name'],0, 10) !== 'admin.api.') {
                    continue;
                }

                $routeName = \str_replace(' ', '', \ucwords(\str_replace('.', ' ', substr($route['name'], 10))));
                $routeName[0] = \mb_strtolower($routeName[0]);
                $routes[$routeName] = $this->adminConfig->getUri()->getPath() .  '/api' . $route['path'];
            }
        }


        return $routes;
    }
}
