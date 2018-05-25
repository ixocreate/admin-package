<?php

namespace KiwiSuite\Admin\Form;

class Element
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $type;

    protected $readonly;

    protected $required;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * Element constructor.
     * @param string $name
     * @param string $label
     * @param string $type
     * @param array $options
     */
    public function __construct(string $name, string $label, string $type, array $options = [])
    {
        $this->name = $name;
        $this->label = $label;
        $this->type = $type;
        $this->options = $options;
    }

    public function addOption(string $key, $value)
    {
        $this->options[$key] = $value;
        return $this;
    }

    public function toArray()
    {
        return [
            'key'    => $this->name,
            'type'    => $this->type,
            'templateOptions' => \array_merge(
                ['label'   => $this->label],
                $this->options
            ),
        ];
    }
}