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
use KiwiSuite\Admin\Response\ApiSuccessResponse;
use KiwiSuite\ApplicationHttp\Pipe\Config\SegmentConfig;
use KiwiSuite\ApplicationHttp\Pipe\Config\SegmentPipeConfig;
use KiwiSuite\ApplicationHttp\Pipe\PipeConfig;
use KiwiSuite\Intl\LocaleManager;
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
     * @var LocaleManager
     */
    private $localeManager;

    /**
     * ConfigAction constructor.
     * @param AdminConfig $adminConfig
     * @param PipeConfig $pipeConfig
     * @param ServerUrlHelper $serverUrlHelper
     * @param UrlHelper $urlHelper
     * @param LocaleManager $localeManager
     */
    public function __construct(
        AdminConfig $adminConfig,
        PipeConfig $pipeConfig,
        ServerUrlHelper $serverUrlHelper,
        UrlHelper $urlHelper,
        LocaleManager $localeManager
    ) {
        $this->adminConfig = $adminConfig;
        $this->serverUrlHelper = $serverUrlHelper;
        $this->pipeConfig = $pipeConfig;
        $this->urlHelper = $urlHelper;
        $this->localeManager = $localeManager;
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
            'project' => [
                'author' => $this->adminConfig->author(),
                'name' => $this->adminConfig->name(),
                'poweredBy' => $this->adminConfig->poweredBy(),
                'copyright' => $this->adminConfig->copyright(),
                'description' => $this->adminConfig->description(),
                'background' => $this->adminConfig->background(),
                'icon' => $this->adminConfig->icon(),
                'logo' => $this->adminConfig->logo(),
            ],
            'navigation' => $this->getNavigation(),
            'intl' => [
                'default' => $this->localeManager->defaultLocale(),
                'locales' => $this->localeManager->all(),
            ],
        ]);
    }

    /**
     * @return array
     */
    private function getNavigation(): array
    {
        $navigationConfig = $this->adminConfig->navigation();

        $navigation = [];

        foreach ($navigationConfig as $navigationEntry) {
            /**
             * TODO: manipulate/filter by user role/permissions
             */
            $navigation[] = $navigationEntry;
        }

        return $navigation;
    }

    /**
     * @return array
     */
    private function getRoutes(): array
    {
        $routes = [];
        $pipeConfig = null;

        foreach ($this->pipeConfig->getMiddlewarePipe() as $pipe) {
            if (!($pipe instanceof SegmentPipeConfig)) {
                continue;
            }

            if ($pipe->provider() !== AdminConfig::class) {
                continue;
            }

            foreach ($pipe->pipeConfig()->getMiddlewarePipe() as $innerPipe) {
                if (!($innerPipe instanceof SegmentConfig)) {
                    continue;
                }

                if ($innerPipe->segment() !== '/api') {
                    continue;
                }

                $pipeConfig = $innerPipe->pipeConfig();
                break;
            }
            break;
        }

        if (empty($pipeConfig)) {
            return $routes;
        }

        foreach ($pipeConfig->getRoutes() as $route) {
            if (\mb_substr($route['name'], 0, 10) !== 'admin.api.') {
                continue;
            }

            $routeName = \str_replace(' ', '', \ucwords(\str_replace('.', ' ', \mb_substr($route['name'], 10))));
            $routeName[0] = \mb_strtolower($routeName[0]);
            $routes[$routeName] = rtrim((string)$this->adminConfig->uri()->getPath(), '/') . '/api' . $route['path'];
        }
        return $routes;
    }
}
