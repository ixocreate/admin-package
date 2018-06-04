<?php
declare(strict_types=1);
namespace KiwiSuite\Admin\Schema\Form\Elements;

use KiwiSuite\Admin\Schema\Form\ElementSubManager;
use KiwiSuite\Admin\Schema\Form\TypeMapping;

final class DynamicGroup extends Container
{

    /**
     * DynamicGroup constructor.
     * @param ElementSubManager $elementSubManager
     * @param TypeMapping $typeMapping
     */
    public function __construct(ElementSubManager $elementSubManager, TypeMapping $typeMapping)
    {
        parent::__construct($elementSubManager, $typeMapping);
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
