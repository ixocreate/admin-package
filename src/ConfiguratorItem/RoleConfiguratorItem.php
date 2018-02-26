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

use KiwiSuite\Admin\Role\RoleInterface;
use KiwiSuite\Admin\Role\RoleServiceManagerConfig;
use KiwiSuite\Application\ConfiguratorItem\ConfiguratorItemInterface;
use KiwiSuite\ServiceManager\Exception\InvalidArgumentException;
use KiwiSuite\ServiceManager\ServiceManagerConfigurator;

final class RoleConfiguratorItem implements ConfiguratorItemInterface
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
        return 'adminRoleConfigurator';
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return 'admin_role.php';
    }

    /**
     * @param  ServiceManagerConfigurator $configurator
     * @return \Serializable
     */
    public function getService($configurator): \Serializable
    {
        $config = $configurator->getServiceManagerConfig();

        $factories = $configurator->getFactories();

        $roleMapping = [];
        foreach ($factories as $id => $factory) {
            if (!\is_subclass_of($id, RoleInterface::class, true)) {
                throw new InvalidArgumentException(\sprintf("'%s' doesn't implement '%s'", $id, RoleInterface::class));
            }
            $roleName = \forward_static_call([$id, 'getName']);
            $roleMapping[$roleName] = $id;
        }

        return new RoleServiceManagerConfig(
            $roleMapping,
            $config->getFactories(),
            $config->getSubManagers(),
            $config->getDelegators(),
            $config->getLazyServices(),
            $config->getDisabledSharing(),
            $config->getInitializers()
        );
    }
}
