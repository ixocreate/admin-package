<?php

namespace KiwiSuite\Admin\Schema\Form;


final class DynamicGroup implements ElementInterface
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
     * DynamicGroup constructor.
     * @param string $name
     * @param string $label
     */
    public function __construct(string $name, string $label)
    {
        $this->element = new Element($name, $label, "dynamic");
        $this->element->addOption("btnText", "");
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

    public function addElement(ElementGroup $element)
    {
        $this->elements[] = $element;
        return $this;
    }

    public function toArray(): array
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

        $array = $this->element->toArray();
        $array['fieldArray'] = [];
        $array['fieldGroups'] = $elements;

        return $array;
    }
}
