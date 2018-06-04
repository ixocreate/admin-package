<?php

namespace KiwiSuite\Admin\Schema\Form\Elements;

final class Wysiwyg extends AbstractProxyElement
{
    /**
     * Wysiwyg constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->element->setType("wysiwyg");
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
}
