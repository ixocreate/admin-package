<?php

namespace KiwiSuite\Admin\Schema\Form\Elements;

final class Number extends AbstractProxyElement
{
    /**
     * Wysiwyg constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->element->setType("input");
        $this->element->addOption("type", "number");
    }
}
