<?php

namespace KiwiSuite\Admin\Schema\Form\Elements;

final class Select extends AbstractProxyElement
{
    /**
     * Wysiwyg constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->element->setType("select");
    }

    public function setSelectOptions(array $options): Select
    {
        $this->addOption("options", $options);

        return $this;
    }
}
