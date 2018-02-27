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

use KiwiSuite\Admin\Resource\ResourceInterface;
use KiwiSuite\Admin\Resource\ResourceServiceManagerConfig;
use KiwiSuite\Application\ConfiguratorItem\ConfiguratorItemInterface;
use KiwiSuite\ServiceManager\ServiceManagerConfigurator;

final class ResourceConfiguratorItem implements ConfiguratorItemInterface
{
    /**
     * @return mixed
     */
    public function getConfigurator()
    {
        return new ServiceManagerConfigurator();
    }

    /**
     * @return string
     */
    public function getVariableName(): string
    {
        return 'resourceConfigurator';
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return 'resource.php';
    }

    /**
     * @param  ServiceManagerConfigurator $configurator
     * @return \Serializable
     */
    public function getService($configurator): \Serializable
    {
        $config = $configurator->getServiceManagerConfig();

        $factories = $configurator->getFactories();

        $resourceMapping = [];
        foreach ($factories as $id => $factory) {
            if (!\is_subclass_of($id, ResourceInterface::class, true)) {
                throw new \InvalidArgumentException(\sprintf("'%s' doesn't implement '%s'", $id, ResourceInterface::class));
            }
            $name = \forward_static_call([$id, 'name']);
            $resourceMapping[$name] = $id;
        }

        return new ResourceServiceManagerConfig(
            $resourceMapping,
            $config->getFactories(),
            $config->getSubManagers(),
            $config->getDelegators(),
            $config->getLazyServices(),
            $config->getDisabledSharing(),
            $config->getInitializers()
        );
    }
}
