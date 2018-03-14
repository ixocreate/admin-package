<?php
namespace KiwiSuite\Admin\Role\Factory;

use KiwiSuite\Admin\Role\RoleInterface;
use KiwiSuite\Admin\Role\RoleServiceManagerConfig;
use KiwiSuite\Admin\Role\RoleSubManager;
use KiwiSuite\Contract\ServiceManager\ServiceManagerInterface;
use KiwiSuite\Contract\ServiceManager\SubManager\SubManagerFactoryInterface;
use KiwiSuite\Contract\ServiceManager\SubManager\SubManagerInterface;

final class RoleSubManagerFactory implements SubManagerFactoryInterface
{

    /**
     * @param ServiceManagerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return SubManagerInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ServiceManagerInterface $container, $requestedName, array $options = null): SubManagerInterface
    {
        return new RoleSubManager(
            $container,
            $container->get(RoleServiceManagerConfig::class),
            RoleInterface::class
        );
    }
}
