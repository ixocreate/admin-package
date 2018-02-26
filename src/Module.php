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
use KiwiSuite\Admin\ConfiguratorItem\RoleConfiguratorItem;
use KiwiSuite\Admin\Role\Factory\RoleSubManagerFactory;
use KiwiSuite\Admin\Role\RoleSubManager;
use KiwiSuite\Application\ConfiguratorItem\ConfiguratorRegistry;
use KiwiSuite\Application\ConfiguratorItem\ServiceManagerConfiguratorItem;
use KiwiSuite\Application\Module\ModuleInterface;
use KiwiSuite\Application\Service\ServiceRegistry;
use KiwiSuite\ServiceManager\ServiceManager;
use KiwiSuite\ServiceManager\ServiceManagerConfigurator;
use KiwiSuite\Template\Plates\PlatesRendererFactory;
use Zend\Expressive\Plates\PlatesRenderer;

class Module implements ModuleInterface
{
    /**
     * @param ConfiguratorRegistry $configuratorRegistry
     */
    public function configure(ConfiguratorRegistry $configuratorRegistry): void
    {
        /** @var ServiceManagerConfigurator $serviceManagerConfigurator */
        $serviceManagerConfigurator = $configuratorRegistry->get(ServiceManagerConfiguratorItem::class);
        $serviceManagerConfigurator->addSubManager(RoleSubManager::class, RoleSubManagerFactory::class);

        $serviceManagerConfigurator->addFactory(PlatesRenderer::class, PlatesRendererFactory::class);
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
