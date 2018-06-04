<?php

namespace KiwiSuite\Admin\Schema\Form\Elements;

final class Media extends AbstractProxyElement
{
    /**
     * Wysiwyg constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->element->setType("media");
    }
}
