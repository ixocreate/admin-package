<?php
namespace KiwiSuite\Admin;

use KiwiSuite\Admin\Type\RoleType;
use KiwiSuite\ServiceManager\ServiceManagerConfigurator;

/** @var ServiceManagerConfigurator $typeConfigurator */
$typeConfigurator->addFactory(RoleType::class);
