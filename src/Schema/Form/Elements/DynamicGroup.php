<?php
declare(strict_types=1);
namespace KiwiSuite\Admin\Schema\Form\Elements;

use KiwiSuite\Admin\Schema\Form\ElementInterface;
use KiwiSuite\Admin\Schema\Form\ElementSubManager;

final class DynamicGroup extends Container
{

    /**
     * DynamicGroup constructor.
     * @param ElementSubManager $elementSubManager
     */
    public function __construct(ElementSubManager $elementSubManager)
    {
        parent::__construct($elementSubManager);
        $this->element->setType("dynamic");
        $this->element->addOption("btnText", "");
    }

    public function toArray(): array
    {
        $elements = array_filter($this->elements(), function($value) {
           return ($value instanceof ElementGroup);
        });
        $elements = array_map(function (ElementGroup $element) {
            $array = $element->toArray();
            $array['_type'] = $this->getType();

            return $array;
        }, $elements);

        $array = parent::toArray();
        $array['fieldArray'] = [];
        $array['fieldGroups'] = $elements;

        return $array;
    }
}
