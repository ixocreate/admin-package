<?php
declare(strict_types=1);

namespace KiwiSuite\Admin;

/** @var ElementConfigurator $element */
use KiwiSuite\Admin\Schema\Form\ElementConfigurator;
use KiwiSuite\Admin\Schema\Form\Elements\Container;
use KiwiSuite\Admin\Schema\Form\Elements\Date;
use KiwiSuite\Admin\Schema\Form\Elements\DateTime;
use KiwiSuite\Admin\Schema\Form\Elements\DynamicGroup;
use KiwiSuite\Admin\Schema\Form\Elements\ElementGroup;
use KiwiSuite\Admin\Schema\Form\Elements\Form;
use KiwiSuite\Admin\Schema\Form\Elements\Image;
use KiwiSuite\Admin\Schema\Form\Elements\Select;
use KiwiSuite\Admin\Schema\Form\Elements\Text;
use KiwiSuite\Admin\Schema\Form\Elements\Wysiwyg;

$element->addElement(Container::class);
$element->addElement(DynamicGroup::class);
$element->addElement(ElementGroup::class);
$element->addElement(Form::class);
$element->addElement(Text::class);
$element->addElement(Select::class);
$element->addElement(DateTime::class);
$element->addElement(Date::class);
$element->addElement(Image::class);
$element->addElement(Wysiwyg::class);
