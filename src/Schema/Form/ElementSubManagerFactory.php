<?php
namespace KiwiSuite\Admin\Schema\Form;

use KiwiSuite\Admin\Schema\Form\Elements\Container;
use KiwiSuite\Admin\Schema\Form\Elements\Date;
use KiwiSuite\Admin\Schema\Form\Elements\DateTime;
use KiwiSuite\Admin\Schema\Form\Elements\DynamicGroup;
use KiwiSuite\Admin\Schema\Form\Elements\ElementGroup;
use KiwiSuite\Admin\Schema\Form\Elements\Form;
use KiwiSuite\Admin\Schema\Form\Elements\Media;
use KiwiSuite\Admin\Schema\Form\Elements\Select;
use KiwiSuite\Admin\Schema\Form\Elements\Text;
use KiwiSuite\Admin\Schema\Form\Elements\Wysiwyg;
use KiwiSuite\Contract\ServiceManager\ServiceManagerInterface;
use KiwiSuite\Contract\ServiceManager\SubManager\SubManagerFactoryInterface;
use KiwiSuite\Contract\ServiceManager\SubManager\SubManagerInterface;
use KiwiSuite\ServiceManager\SubManager\SubManagerConfigurator;

final class ElementSubManagerFactory implements SubManagerFactoryInterface
{

    public function __invoke(ServiceManagerInterface $container, $requestedName, array $options = null): SubManagerInterface
    {
        $subManagerConfigurator = new SubManagerConfigurator(ElementSubManager::class, ElementInterface::class);
        $subManagerConfigurator->addFactory(Container::class);
        $subManagerConfigurator->addFactory(DynamicGroup::class);
        $subManagerConfigurator->addFactory(ElementGroup::class);
        $subManagerConfigurator->addFactory(Form::class);
        $subManagerConfigurator->addFactory(Text::class);
        $subManagerConfigurator->addFactory(Select::class);
        $subManagerConfigurator->addFactory(DateTime::class);
        $subManagerConfigurator->addFactory(Date::class);
        $subManagerConfigurator->addFactory(Media::class);
        $subManagerConfigurator->addFactory(Wysiwyg::class);

        return new ElementSubManager($container, $subManagerConfigurator->getServiceManagerConfig(), ElementInterface::class);
    }
}
