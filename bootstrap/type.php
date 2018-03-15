<?php
namespace KiwiSuite\Admin;

use KiwiSuite\Admin\Type\RoleType;
use KiwiSuite\Admin\Type\StatusType;
use KiwiSuite\Entity\Type\TypeConfigurator;

/** @var TypeConfigurator $type */
$type->addType(RoleType::class);
$type->addType(StatusType::class);
