<?php
namespace KiwiSuite\Admin;

use Doctrine\DBAL\Types\StringType;
use KiwiSuite\Admin\Type\RoleType;
use KiwiSuite\Database\Type\TypeConfigurator;

/** @var TypeConfigurator $databaseTypeConfigurator */
$databaseTypeConfigurator->addType(RoleType::class, StringType::class);
