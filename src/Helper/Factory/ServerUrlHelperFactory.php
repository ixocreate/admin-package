<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Package\Helper\Factory;

use Ixocreate\Admin\Package\Config\AdminConfig;
use Ixocreate\Admin\Package\Helper\ServerUrlHelper;
use Ixocreate\ServiceManager\FactoryInterface;
use Ixocreate\ServiceManager\ServiceManagerInterface;

final class ServerUrlHelperFactory implements FactoryInterface
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
        $serverUrlHelper = new ServerUrlHelper();
        $serverUrlHelper->setUri($container->get(AdminConfig::class)->getUri());

        return $serverUrlHelper;
    }
}
