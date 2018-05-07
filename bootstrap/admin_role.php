<?php
namespace KiwiSuite\Admin;

use KiwiSuite\Admin\Role\AdministratorRole;
use KiwiSuite\Admin\Role\RoleConfigurator;
/** @var RoleConfigurator $role */

$role->addRole(AdministratorRole::class);
$role->addDirectory(getcwd() . '/src/Admin/Role', true);
