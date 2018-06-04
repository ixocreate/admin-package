<?php
declare(strict_types=1);
namespace KiwiSuite\Admin\Schema\Form;

use KiwiSuite\Contract\Application\ConfiguratorInterface;
use KiwiSuite\Contract\Application\ServiceRegistryInterface;
use KiwiSuite\ServiceManager\Factory\AutowireFactory;
use KiwiSuite\ServiceManager\SubManager\SubManagerConfigurator;

final class ElementConfigurator implements ConfiguratorInterface
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
        $this->subManagerConfigurator = new SubManagerConfigurator(ElementSubManager::class, ElementInterface::class);
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
     * @param string $element
     * @param string $factory
     */
    public function addElement(string $element, string $factory = AutowireFactory::class): void
    {
        $this->subManagerConfigurator->addFactory($element, $factory);
    }

    /**
     * @param ServiceRegistryInterface $serviceRegistry
     * @return void
     */
    public function registerService(ServiceRegistryInterface $serviceRegistry): void
    {
        $factories = $this->subManagerConfigurator->getServiceManagerConfig()->getFactories();

        $commandMap = [];
        foreach ($factories as $id => $factory) {
            if (!\is_subclass_of($id, TypeMappingInterface::class, true)) {
                continue;
            }
            $type = \forward_static_call([$id, 'getTypeMapping']);
            $commandMap[$type] = $id;
        }

        $serviceRegistry->add(TypeMapping::class, new TypeMapping($commandMap));
        $this->subManagerConfigurator->registerService($serviceRegistry);
    }
}
