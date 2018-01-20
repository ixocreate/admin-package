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

namespace KiwiSuite\Admin\ConfiguratorItem;

use KiwiSuite\Admin\Route\RouteConfig;
use KiwiSuite\Application\ConfiguratorItem\ConfiguratorItemInterface;
use KiwiSuite\ApplicationHttp\Route\RouteConfigurator;
use KiwiSuite\ServiceManager\ServiceManagerConfigurator;

final class RouteConfiguratorItem implements ConfiguratorItemInterface
{
    public function configureServiceManager(ServiceManagerConfigurator $serviceManagerConfigurator): void
    {
    }

    /**
     * @return mixed
     */
    public function getConfigurator()
    {
        return new RouteConfigurator(RouteConfig::class);
    }

    /**
     * @return string
     */
    public function getConfiguratorName(): string
    {
        return 'adminRouteConfigurator';
    }

    /**
     * @return string
     */
    public function getConfiguratorFileName(): string
    {
        return 'admin_route.php';
    }

    /**
     * @param RouteConfigurator $configurator
     * @return \Serializable
     */
    public function getService($configurator): \Serializable
    {
        return $configurator->getRouteConfig();
    }
}
