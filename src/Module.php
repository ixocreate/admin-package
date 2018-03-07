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

namespace KiwiSuite\Admin;

use KiwiSuite\Admin\ConfiguratorItem\PipeConfiguratorItem;
use KiwiSuite\Admin\ConfiguratorItem\ResourceConfiguratorItem;
use KiwiSuite\Admin\ConfiguratorItem\RoleConfiguratorItem;
use KiwiSuite\Admin\Resource\RoutingSetup;
use KiwiSuite\Admin\Resource\UserResource;
use KiwiSuite\Application\ConfiguratorItem\ConfiguratorRegistry;
use KiwiSuite\Application\Module\ModuleInterface;
use KiwiSuite\Application\Service\ServiceRegistry;
use KiwiSuite\ServiceManager\ServiceManager;


class Module implements ModuleInterface
{
    /**
     * @param ConfiguratorRegistry $configuratorRegistry
     */
    public function configure(ConfiguratorRegistry $configuratorRegistry): void
    {
        $configuratorRegistry->get(ResourceConfiguratorItem::class)->addFactory(UserResource::class);
        $routingSetup = new RoutingSetup();
        $routingSetup->setup(
            $configuratorRegistry->get(PipeConfiguratorItem::class),
            (new ResourceConfiguratorItem())->getService($configuratorRegistry->get(ResourceConfiguratorItem::class))
        );
    }

    /**
     * @param ServiceRegistry $serviceRegistry
     */
    public function addServices(ServiceRegistry $serviceRegistry): void
    {
        //
    }

    /**
     * @return array|null
     */
    public function getConfiguratorItems(): ?array
    {
        return [
            PipeConfiguratorItem::class,
            RoleConfiguratorItem::class,
            ResourceConfiguratorItem::class,
        ];
    }

    /**
     * @return array|null
     */
    public function getDefaultConfig(): ?array
    {
        return null;
    }

    /**
     * @param ServiceManager $serviceManager
     */
    public function boot(ServiceManager $serviceManager): void
    {
        //
    }

    /**
     * @return null|string
     */
    public function getBootstrapDirectory(): ?string
    {
        return __DIR__ . '/../bootstrap/';
    }

    /**
     * @return null|string
     */
    public function getConfigDirectory(): ?string
    {
        return __DIR__ . '/../config/';
    }

    /**
     * @return array|null
     */
    public function getBootstrapItems(): ?array
    {
        return null;
    }
}
