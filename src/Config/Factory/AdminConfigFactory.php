<?php
namespace KiwiSuite\Admin\Config\Factory;

use KiwiSuite\Admin\Config\AdminConfig;
use KiwiSuite\Config\Config;
use KiwiSuite\ServiceManager\FactoryInterface;
use KiwiSuite\ServiceManager\ServiceManagerInterface;
use Zend\Diactoros\Uri;

final class AdminConfigFactory implements FactoryInterface
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
        /** @var Config $config */
        $config = $container->get(Config::class);

        return new AdminConfig(
            new Uri($config->get("admin.uri")),
            $config->get("admin.apiBasePath"),
            $config->get("admin.project")
        );
    }
}
