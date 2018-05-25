<?php

namespace KiwiSuite\Admin\Form;

class ElementGroup extends Element implements \Iterator
{
    /**
     * @var array
     */
    protected $elements = [];

    private $position = 0;

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
            'key'    => $this->name,
            'type'    => $this->type,
            'templateOptions' => \array_merge(
                ['label'   => $this->label],
                $this->options
            ),
            'fieldGroup' => $elements,
        ];
    }

    public function current()
    {
        return $this->elements[$this->position];
    }

    public function next()
    {
        $this->position++;
    }

    public function key()
    {
        return $this->position;
    }

    public function valid()
    {
        return isset($this->elements[$this->position]);
    }

    public function rewind()
    {
        $this->position = 0;
    }
}
