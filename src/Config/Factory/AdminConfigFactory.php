<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Package\Admin\Config\Factory;

use Ixocreate\Package\Admin\Config\AdminConfig;
use Ixocreate\Package\Admin\Config\AdminProjectConfig;
use Ixocreate\Package\Asset\Asset;
use Ixocreate\Config\Config;
use Ixocreate\ServiceManager\FactoryInterface;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use Ixocreate\Package\ProjectUri\ProjectUri;
use Zend\Diactoros\Uri;

final class AdminConfigFactory implements FactoryInterface
{
    /**
     * @param ServiceManagerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return mixed
     */
    public function __invoke(ServiceManagerInterface $container, $requestedName, array $options = null)
    {
        /** @var Config $config */
        $config = $container->get(Config::class);

        $uri = new Uri($config->get("admin.uri"));
        if (empty($uri->getHost())) {
            /** @var ProjectUri $projectUri */
            $projectUri = $container->get(ProjectUri::class);

            $uri = $uri->withPath($projectUri->getMainUri()->getPath() . '/' . $uri->getPath());
            $uri = $uri->withHost($projectUri->getMainUri()->getHost());
            $uri = $uri->withScheme($projectUri->getMainUri()->getScheme());
            $uri = $uri->withPort($projectUri->getMainUri()->getPort());
        }

        return new AdminConfig($container->get(AdminProjectConfig::class), $uri, $container->get(Asset::class));
    }
}
