<?php

namespace KiwiSuite\Admin\Schema;

use KiwiSuite\Admin\Schema\Form\ElementInterface;
use KiwiSuite\Admin\Schema\Form\FormBuilder;

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
     * @var FormBuilder
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

    public function addElement(ElementInterface $element)
    {
        $this->elements[] = $element;
        return $this;
    }

    public function toArray()
    {
        return [
            'name'       => $this->name,
            'namePlural' => $this->namePlural,
            'list'       => $this->list,
            'filter'     => $this->filter,
            'form'       => $this->elements->toArray(),
        ];
    }
}
