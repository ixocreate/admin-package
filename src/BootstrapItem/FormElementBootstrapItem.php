<?php
declare(strict_types=1);
namespace KiwiSuite\Admin\BootstrapItem;

use KiwiSuite\Admin\Schema\Form\ElementConfigurator;
use KiwiSuite\Contract\Application\BootstrapItemInterface;
use KiwiSuite\Contract\Application\ConfiguratorInterface;

final class FormElementBootstrapItem implements BootstrapItemInterface
{

    /**
     * @return mixed
     */
    public function getConfigurator(): ConfiguratorInterface
    {
        return new ElementConfigurator();
    }

    /**
     * @return string
     */
    public function getVariableName(): string
    {
        return 'element';
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return 'form_element.php';
    }
}
