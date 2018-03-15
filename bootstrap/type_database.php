<?php
namespace KiwiSuite\Admin;

use Doctrine\DBAL\Types\StringType;
use KiwiSuite\Admin\Type\RoleType;
use KiwiSuite\Admin\Type\StatusType;
use KiwiSuite\Database\Type\TypeConfigurator;

/** @var TypeConfigurator $type */
$type->addType(RoleType::class, StringType::class);
$type->addType(StatusType::class, StringType::class);
