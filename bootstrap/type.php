<?php
namespace KiwiSuite\Admin;

use KiwiSuite\Admin\Type\RoleType;
use KiwiSuite\Admin\Type\StatusType;
use KiwiSuite\ServiceManager\ServiceManagerConfigurator;

/** @var ServiceManagerConfigurator $typeConfigurator */
$typeConfigurator->addFactory(RoleType::class);
$typeConfigurator->addFactory(StatusType::class);
