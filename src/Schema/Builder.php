<?php

namespace KiwiSuite\Admin\Schema;

use KiwiSuite\Admin\Form\Element;
use KiwiSuite\Admin\Form\ElementGroup;

class Builder
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $namePlural;

    /**
     * @var array
     */
    protected $list = [];

    /**
     * @var array
     */
    protected $filter = [];

    /**
     * @var ElementGroup
     */
    protected $elements;

    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    public function setNamePlural(string $namePlural)
    {
        $this->namePlural = $namePlural;
        return $this;
    }

    public function addListField(string $key, string $label)
    {
        $this->list[] = [
            'key' => $key,
            'name' => $label,
        ];
        return $this;
    }

    public function setElements(array $elements)
    {
        $this->elements = $elements;
        return $this;
    }

    public function addElement(Element $element)
    {
        $this->elements[] = $element;
        return $this;
    }

    public function toArray()
    {
        $elements = [];
        foreach ($this->elements as $element) {
            /** @var Element $element */
            $elements[] = $element->toArray();
        }

        return [
            'name'       => $this->name,
            'namePlural' => $this->namePlural,
            'list'       => $this->list,
            'filter'     => $this->filter,
            'form'       => $elements,
        ];
    }
}
