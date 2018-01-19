<?php
namespace KiwiSuite\Admin\Middleware\Factory;

use KiwiSuite\ApplicationHttp\Pipe\PipeConfig;
use KiwiSuite\ApplicationHttp\Route\RouteConfig;
use KiwiSuite\ServiceManager\FactoryInterface;
use KiwiSuite\ServiceManager\ServiceManagerInterface;
use Zend\Expressive\Application;

final class AdminApplicationFactory implements FactoryInterface
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
        return $container->build(Application::class, [
            PipeConfig::class => $container->get(\KiwiSuite\Admin\Pipe\PipeConfig::class),
            RouteConfig::class => $container->get(\KiwiSuite\Admin\Route\RouteConfig::class),
        ]);
    }
}
