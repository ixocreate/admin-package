<?php
namespace KiwiSuite\Admin\Helper\Factory;

use KiwiSuite\Admin\Helper\UrlHelper;
use KiwiSuite\Admin\Router\AdminRouter;
use KiwiSuite\ServiceManager\FactoryInterface;
use KiwiSuite\ServiceManager\ServiceManagerInterface;

final class UrlHelperFactory implements FactoryInterface
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
        return new UrlHelper($container->get(AdminRouter::class));
    }
}
