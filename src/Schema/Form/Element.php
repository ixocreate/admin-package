<?php

namespace KiwiSuite\Admin\Schema\Form;

final class Element implements ElementInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $type;

    /**
     * @var bool
     */
    private $readonly = false;

    /**
     * @var bool
     */
    private $required = false;

    /**
     * @var array
     */
    private $options = [];

    /**
     * Element constructor.
     * @param string $name
     * @param string $label
     * @param string $type
     * @param array $options
     */
    public function __construct(string $name, string $label, string $type)
    {
        $this->name = $name;
        $this->label = $label;
        $this->type = $type;
    }

    /**
     * @param string $key
     * @param $value
     * @return ElementInterface
     */
    public function addOption(string $key, $value): ElementInterface
    {
        $this->options[$key] = $value;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'key'    => $this->name,
            'type'    => $this->type,
            'templateOptions' => \array_merge(
                [
                    'label'   => $this->label,
                    'placeholder'   => $this->label,
                    'required' => $this->required,
                    'readonly' => $this->readonly,
                ],
                $this->options
            ),
        ];
    }

    /**
     * @param bool $required
     * @return ElementInterface
     */
    public function setRequired(bool $required): ElementInterface
    {
        $this->required = $required;

        return $this;
    }

    /**
     * @param bool $readonly
     * @return ElementInterface
     */
    public function setReadonly(bool $readonly): ElementInterface
    {
        $this->readonly = $readonly;

        return $this;
    }
}
