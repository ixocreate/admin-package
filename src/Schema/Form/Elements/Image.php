<?php

namespace KiwiSuite\Admin\Schema\Form\Elements;

use KiwiSuite\Admin\Schema\Form\TypeMappingInterface;
use KiwiSuite\Media\Type\ImageType;

final class Image extends AbstractProxyElement implements TypeMappingInterface
{
    /**
     * Wysiwyg constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->element->setType("media");
    }

    public static function getTypeMapping(): string
    {
        return ImageType::class;
    }
}
