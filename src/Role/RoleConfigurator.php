<?php
/**
 * kiwi-suite/admin (https://github.com/kiwi-suite/admin)
 *
 * @package kiwi-suite/admin
 * @link https://github.com/kiwi-suite/admin
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace KiwiSuite\Admin\Role;

use KiwiSuite\Contract\Application\ConfiguratorInterface;
use KiwiSuite\Contract\Application\ServiceRegistryInterface;
use KiwiSuite\ServiceManager\Factory\AutowireFactory;
use KiwiSuite\ServiceManager\SubManager\SubManagerConfigurator;

final class RoleConfigurator implements ConfiguratorInterface
{
    /**
     * @var SubManagerConfigurator
     */
    private $subManagerConfigurator;

    /**
     * MiddlewareConfigurator constructor.
     */
    public function __construct()
    {
        $this->subManagerConfigurator = new SubManagerConfigurator(RoleSubManager::class, \KiwiSuite\Contract\Admin\RoleInterface::class);
    }

    /**
     * @param string $directory
     * @param bool $recursive
     */
    public function addDirectory(string $directory, bool $recursive = true): void
    {
        $this->subManagerConfigurator->addDirectory($directory, $recursive);
    }

    /**
     * @param string $action
     * @param string $factory
     */
    public function addRole(string $action, string $factory = AutowireFactory::class): void
    {
        $this->subManagerConfigurator->addFactory($action, $factory);
    }

    /**
     * @return SubManagerConfigurator
     */
    public function getManagerConfigurator(): SubManagerConfigurator
    {
        return $this->subManagerConfigurator;
    }

    /**
     * @param ServiceRegistryInterface $serviceRegistry
     * @return void
     */
    public function registerService(ServiceRegistryInterface $serviceRegistry): void
    {
        $config = $this->getManagerConfigurator();

        $factories = $config->getServiceManagerConfig()->getFactories();

        $roleMapping = [];
        foreach ($factories as $id => $factory) {
            if (!\is_subclass_of($id, \KiwiSuite\Contract\Admin\RoleInterface::class, true)) {
                throw new \InvalidArgumentException(\sprintf("'%s' doesn't implement '%s'", $id, \KiwiSuite\Contract\Admin\RoleInterface::class));
            }
            $roleName = \forward_static_call([$id, 'getName']);
            $roleMapping[$roleName] = $id;
        }
        $serviceRegistry->add(RoleMapping::class, new RoleMapping($roleMapping));
        $this->subManagerConfigurator->registerService($serviceRegistry);
    }
}
