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

namespace KiwiSuite\Admin\Resource;

use KiwiSuite\Contract\Application\ConfiguratorInterface;
use KiwiSuite\Contract\Application\ServiceRegistryInterface;
use KiwiSuite\ServiceManager\Factory\AutowireFactory;
use KiwiSuite\ServiceManager\SubManager\SubManagerConfigurator;

final class ResourceConfigurator implements ConfiguratorInterface
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
        $this->subManagerConfigurator = new SubManagerConfigurator(ResourceSubManager::class, ResourceInterface::class);
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
    public function addResource(string $action, string $factory = AutowireFactory::class): void
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
     * @return ResourceMapping
     */
    public function getResourceMapping(): ResourceMapping
    {
        $config = $this->getManagerConfigurator();

        $factories = $config->getServiceManagerConfig()->getFactories();

        $resourceMapping = [];
        foreach ($factories as $id => $factory) {
            if (!\is_subclass_of($id, ResourceInterface::class, true)) {
                throw new \InvalidArgumentException(\sprintf("'%s' doesn't implement '%s'", $id, ResourceInterface::class));
            }
            $name = \forward_static_call([$id, 'name']);
            $resourceMapping[$name] = $id;
        }

        return new ResourceMapping($resourceMapping);
    }

    /**
     * @param ServiceRegistryInterface $serviceRegistry
     * @return void
     */
    public function registerService(ServiceRegistryInterface $serviceRegistry): void
    {
        $serviceRegistry->add(ResourceMapping::class, $this->getResourceMapping());
        $this->subManagerConfigurator->registerService($serviceRegistry);
    }
}
