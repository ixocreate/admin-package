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

use KiwiSuite\Admin\BootstrapItem\FormElementBootstrapItem;
use KiwiSuite\Admin\BootstrapItem\PipeBootstrapItem;
use KiwiSuite\Admin\BootstrapItem\ResourceBootstrapItem;
use KiwiSuite\Admin\BootstrapItem\RoleBootstrapItem;
use KiwiSuite\Admin\Resource\RoutingSetup;
use KiwiSuite\Admin\Resource\UserResource;
use KiwiSuite\Contract\Application\ConfiguratorRegistryInterface;
use KiwiSuite\Contract\Application\PackageInterface;
use KiwiSuite\Contract\Application\ServiceRegistryInterface;
use KiwiSuite\Contract\ServiceManager\ServiceManagerInterface;

class Package implements PackageInterface
{
    /**
     * @param ConfiguratorRegistryInterface $configuratorRegistry
     */
    public function configure(ConfiguratorRegistryInterface $configuratorRegistry): void
    {
        $configuratorRegistry->get(ResourceBootstrapItem::class)->addResource(UserResource::class);
        $routingSetup = new RoutingSetup();
        $routingSetup->setup(
            $configuratorRegistry->get(PipeBootstrapItem::class),
            $configuratorRegistry->get(ResourceBootstrapItem::class)->getResourceMapping()
        );
    }

    /**
     * @param ServiceRegistryInterface $serviceRegistry
     */
    public function addServices(ServiceRegistryInterface $serviceRegistry): void
    {
        //
    }

    /**
     * @return array|null
     */
    public function getBootstrapItems(): ?array
    {
        return [
            PipeBootstrapItem::class,
            RoleBootstrapItem::class,
            ResourceBootstrapItem::class,
        ];
    }

    /**
     * @return array|null
     */
    public function getConfigProvider(): ?array
    {
        return null;
    }

    /**
     * @param ServiceManagerInterface $serviceManager
     */
    public function boot(ServiceManagerInterface $serviceManager): void
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
    public function getDependencies(): ?array
    {
        return [
            \KiwiSuite\Media\Package::class,
            \KiwiSuite\Cms\Package::class,
            \KiwiSuite\Intl\Package::class,
        ];
    }
}
