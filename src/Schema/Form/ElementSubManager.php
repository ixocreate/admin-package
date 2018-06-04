<?php
namespace KiwiSuite\Admin\Schema\Form;

use KiwiSuite\Admin\Schema\Form\Elements\Date;
use KiwiSuite\Admin\Schema\Form\Elements\DateTime;
use KiwiSuite\Admin\Schema\Form\Elements\Media;
use KiwiSuite\Admin\Schema\Form\Elements\Text;
use KiwiSuite\CommonTypes\Entity\DateTimeType;
use KiwiSuite\CommonTypes\Entity\DateType;
use KiwiSuite\Media\Type\ImageType;
use KiwiSuite\ServiceManager\SubManager\SubManager;

final class ElementSubManager extends SubManager
{
    public function typeMappingFor(string $type): string
    {
        $types = [
            ImageType::class => Media::class,
            DateTimeType::class => DateTime::class,
            DateType::class => Date::class,
        ];

        if (array_key_exists($type, $types)) {
            return $types[$type];
        }


        return Text::class;
    }
}
