<?php

namespace KiwiSuite\Admin\Schema\Form\Elements;

use KiwiSuite\Admin\Schema\Form\ElementInterface;

final class Form extends Container
{
    public function toArray(): array
    {
        return array_map(function (ElementInterface $element) {
            return $element->toArray();
        }, $this->elements());
    }
}
