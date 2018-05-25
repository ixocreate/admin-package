<?php

namespace KiwiSuite\Admin\Form;


class Wysiwyg extends Element
{
    /**
     * Wysiwyg constructor.
     * @param string $name
     * @param array $options
     */
    public function __construct(string $name, array $options = [])
    {
        $this->name = $name;
        $this->type = 'wysiwyg';

        $this->options['modules'] = [
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
             ],
         ];

        $this->options = \array_merge($options, $this->options);
    }
}
