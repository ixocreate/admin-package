<?php

namespace KiwiSuite\Admin\Schema\Form\Elements;

use KiwiSuite\Admin\Schema\Form\ElementInterface;

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

    public function setName(string $name): ElementInterface
    {
        $this->name = $name;

        return $this;
    }

    public function setLabel(string $label): ElementInterface
    {
        $this->label = $label;

        return $this;
    }

    public function setType(string $type): ElementInterface
    {
        $this->type = $type;

        return $this;
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

    public function getName(): string
    {
        return $this->name;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function isReadonly(): bool
    {
        return $this->readonly;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
