<?php
namespace KiwiSuite\Admin;

use KiwiSuite\Admin\Role\AdministratorRole;
use KiwiSuite\ServiceManager\ServiceManagerConfigurator;
/** @var ServiceManagerConfigurator $adminRoleConfigurator */

$adminRoleConfigurator->addFactory(AdministratorRole::class);
