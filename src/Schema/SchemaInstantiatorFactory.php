<?php
namespace KiwiSuite\Admin\Schema;

use KiwiSuite\Admin\Schema\Form\ElementSubManager;
use KiwiSuite\Contract\ServiceManager\FactoryInterface;
use KiwiSuite\Contract\ServiceManager\ServiceManagerInterface;

final class SchemaInstantiatorFactory implements FactoryInterface
{

    /**
     * @param ServiceManagerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return mixed
     */
    public function __invoke(ServiceManagerInterface $container, $requestedName, array $options = null)
    {
        return new SchemaInstantiator(
            $container->get(ElementSubManager::class)
        );
    }
}
