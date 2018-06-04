<?php

namespace KiwiSuite\Admin\Schema\Form\Elements;


use KiwiSuite\Admin\Schema\Form\ElementInterface;

final class ElementGroup extends Container
{
    public function setType(string $type): ElementGroup
    {
        $this->element->setType($type);

        return $this;
    }

    public function toArray(): array
    {
        $elements = array_map(function (ElementInterface $element) {
            return $element->toArray();
        }, $this->elements());

        $array = parent::toArray();
        unset($array['key']);
        unset($array['type']);
        unset($array['required']);
        unset($array['readonly']);
        $array['wrappers'] = $this->wrappers();
        $array['fieldGroup'] = $elements;

        return $array;
    }
}
