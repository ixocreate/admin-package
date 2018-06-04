<?php

namespace KiwiSuite\Admin\Schema\Form\Elements;

use KiwiSuite\Admin\Schema\Form\TypeMappingInterface;
use KiwiSuite\CommonTypes\Entity\DateTimeType;

final class DateTime extends AbstractProxyElement implements TypeMappingInterface
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

    public static function getTypeMapping(): string
    {
        return DateTimeType::class;
    }
}
