<?php

namespace KiwiSuite\Admin\Form;


class DynamicGroup extends ElementGroup
{

    /**
     * DynamicGroup constructor.
     */
    public function __construct(string $name, string $label)
    {
        $this->name = $name;
        $this->label = $label;
        $this->type = 'dynamic';
    }

    public function addElement(Element $element)
    {
        if (!$element instanceof ElementGroup) {
            throw new \Exception('Dynamic elements must be a ElementGroup');
        }

        $this->elements[] = $element;
        return $this;
    }

    public function toArray()
    {
        $elements = [];
        foreach ($this->elements as $element) {
            /** @var ElementGroup $element */
            $array = $element->toArray();
            $array['_type'] = $array['key'];
            unset($array['key']);
            unset($array['type']);

            $elements[] = $array;
        }

        return [
            'key'             => $this->name,
            'type'            => $this->type,
            'templateOptions' => [
                 'label'   => $this->label,
                 'btnText' => '',
             ],
             'fieldArray'      => [],
             'fieldGroups'     => $elements
        ];
    }

}
