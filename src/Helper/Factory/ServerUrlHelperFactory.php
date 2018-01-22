<?php
namespace KiwiSuite\Admin\Helper\Factory;

use KiwiSuite\Admin\Config\AdminConfig;
use KiwiSuite\Admin\Helper\ServerUrlHelper;
use KiwiSuite\ServiceManager\FactoryInterface;
use KiwiSuite\ServiceManager\ServiceManagerInterface;

final class ServerUrlHelperFactory implements FactoryInterface
{

    /**
     * @param ServiceManagerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ServiceManagerInterface $container, $requestedName, array $options = null)
    {
        $serverUrlHelper = new ServerUrlHelper();
        $serverUrlHelper->setUri($container->get(AdminConfig::class)->getUri());

        return $serverUrlHelper;
    }
}
