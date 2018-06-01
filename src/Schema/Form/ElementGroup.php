<?php

namespace KiwiSuite\Admin\Schema\Form;

final class ElementGroup implements ElementInterface
{
    /**
     * @var Element
     */
    private $element;

    /**
     * @var array
     */
    protected $elements = [];


    /**
     * Element constructor.
     * @param string $name
     * @param string $label
     * @param string $type
     */
    public function __construct(string $name, string $label, string $type)
    {
        $this->element = new Element($name, $label, $type);
    }

    /**
     * @param string $key
     * @param $value
     * @return ElementInterface
     */
    public function addOption(string $key, $value): ElementInterface
    {
        $this->element->addOption($key, $value);

        return $this;
    }

    /**
     * @param bool $required
     * @return ElementInterface
     */
    public function setRequired(bool $required): ElementInterface
    {
        $this->element->setRequired($required);

        return $this;
    }

    /**
     * @param bool $readonly
     * @return ElementInterface
     */
    public function setReadonly(bool $readonly): ElementInterface
    {
        $this->element->setReadonly($readonly);

        return $this;
    }

    public function addElement(ElementInterface $element): ElementInterface
    {
        $this->elements[] = $element;

        return $this;
    }

    public function toArray(): array
    {
        $elements = [];
        foreach ($this->elements as $element) {
            /** @var Element $element */
            $elements[] = $element->toArray();
        }

        $array = $this->element->toArray();
        $array['fieldGroup'] = $elements;

        return $array;
    }
}
