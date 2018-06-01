<?php
namespace KiwiSuite\Admin\Schema\Form;

final class FormBuilder
{
    private $elements = [];

    public function addElement(ElementInterface $element)
    {
        $this->elements[] = $element;
        return $this;
    }

    public function toArray()
    {
        $elements = [];

        /** @var ElementInterface $item */
        foreach ($this->elements as $item) {
            $elements[] = $item->toArray();
        }

        return $elements;
    }
}
