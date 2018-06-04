<?php

namespace KiwiSuite\Admin\Schema\Form\Elements;

final class DateTime extends AbstractProxyElement
{
    /**
     * Wysiwyg constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->element->setType("datetime");
        $this->element->addOption("config", ['dateInputFormat' => 'YYYY-MM-DD HH:mm:ss']);
    }
}
