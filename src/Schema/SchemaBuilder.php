<?php

namespace KiwiSuite\Admin\Schema;

use KiwiSuite\Admin\Schema\Form\Elements\Form;

final class SchemaBuilder
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $namePlural;

    /**
     * @var array
     */
    private $list = [];

    /**
     * @var array
     */
    private $filter = [];

    /**
     * @var Form
     */
    private $form;

    public function __construct(Form $form)
    {
        $this->form = $form;
    }


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

    public function getForm(): Form
    {
        return $this->form;
    }

    public function toArray()
    {
        return [
            'name'       => $this->name,
            'namePlural' => $this->namePlural,
            'list'       => $this->list,
            'filter'     => $this->filter,
            'form'       => $this->form->toArray(),
        ];
    }
}
