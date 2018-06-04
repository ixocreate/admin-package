<?php

namespace KiwiSuite\Admin\Schema\Form\Elements;

final class Text extends AbstractProxyElement
{
    /**
     * Wysiwyg constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->element->setType("input");
    }
}
