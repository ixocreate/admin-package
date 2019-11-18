<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Config\Client\Provider;

use Ixocreate\Admin\ClientConfigProviderInterface;
use Ixocreate\Admin\Config\AdminConfig;
use Ixocreate\Admin\UserInterface;
use Ixocreate\Application\Http\Pipe\Config\SegmentConfig;
use Ixocreate\Application\Http\Pipe\Config\SegmentPipeConfig;
use Ixocreate\Application\Http\Pipe\PipeConfig;

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

    public static function serviceName(): string
    {
        return 'routes';
    }

    /**
     * @param UserInterface|null $user
     * @return array
     */
    public function clientConfig(?UserInterface $user = null): array
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
            if (\mb_substr($route['name'], 0, 16) === 'admin.api.basic.') {
                $routeName = \mb_substr($route['name'], 16);
            } elseif (\mb_substr($route['name'], 0, 19) === 'admin.api.edituser.') {
                $routeName = \mb_substr($route['name'], 19);
            } else {
                $routeName = \mb_substr($route['name'], 10);
            }

            $routeName = \str_replace(' ', '', \ucwords(\str_replace('.', ' ', $routeName)));
            $routeName[0] = \mb_strtolower($routeName[0]);
            $routes[$routeName] = \rtrim((string)$this->adminConfig->uri()->getPath(), '/') . '/api' . $route['path'];
        }
        return $routes;
    }
}
