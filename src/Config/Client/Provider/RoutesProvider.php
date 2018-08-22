<?php
/**
 * kiwi-suite/admin (https://github.com/kiwi-suite/admin)
 *
 * @package kiwi-suite/admin
 * @link https://github.com/kiwi-suite/admin
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace KiwiSuite\Admin\Config\Client\Provider;

use KiwiSuite\Admin\Config\AdminConfig;
use KiwiSuite\ApplicationHttp\Pipe\Config\SegmentConfig;
use KiwiSuite\ApplicationHttp\Pipe\Config\SegmentPipeConfig;
use KiwiSuite\ApplicationHttp\Pipe\PipeConfig;
use KiwiSuite\Contract\Admin\ClientConfigProviderInterface;
use KiwiSuite\Contract\Admin\RoleInterface;

final class RoutesProvider implements ClientConfigProviderInterface
{
    /**
     * @var PipeConfig
     */
    private $pipeConfig;
    /**
     * @var AdminConfig
     */
    private $adminConfig;

    /**
     * RoutesProvider constructor.
     * @param PipeConfig $pipeConfig
     * @param AdminConfig $adminConfig
     */
    public function __construct(PipeConfig $pipeConfig, AdminConfig $adminConfig)
    {
        $this->pipeConfig = $pipeConfig;
        $this->adminConfig = $adminConfig;
    }

    /**
     * @param RoleInterface|null $role
     * @return array
     */
    public function clientConfig(?RoleInterface $role = null): array
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
            $routes[$routeName] = \rtrim((string)$this->adminConfig->uri()->getPath(), '/') . '/api' . $route['path'];
        }
        return $routes;
    }

    public static function serviceName(): string
    {
        return 'routes';
    }
}
