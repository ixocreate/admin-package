<?php

namespace KiwiSuite\Admin\Schema\Form;


final class Wysiwyg implements ElementInterface
{
    /**
     * @var Element
     */
    private $element;

    /**
     * Wysiwyg constructor.
     * @param string $name
     * @param array $options
     */
    public function __construct(string $name, string $label)
    {
        $this->element = new Element($name, $label, "wysiwyg");
        $this->element->addOption("modules", [
            'toolbar' => [
                ['bold', 'italic', 'underline', 'strike'],
                // toggled buttons
                [
                    ['list' => 'ordered'],
                    ['list' => 'bullet'],
                ],
                [['script' => 'sub'], ['script' => 'super']],
                // superscript/subscript
                [['indent' => '-1'], ['indent' => '+1']],
                // outdent/indent
                [['header' => [1, 2, 3, 4, 5, 6, false]]],
                [['align' => []]],
                ['clean'],
                // remove formatting button
                ['link'],
            ]
        ]);
    }


    public function toArray(): array
    {
        return $this->element->toArray();
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
}
