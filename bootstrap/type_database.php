<?php
namespace KiwiSuite\Admin;

use Doctrine\DBAL\Types\StringType;
use KiwiSuite\Admin\Type\RoleType;
use KiwiSuite\Admin\Type\StatusType;
use KiwiSuite\Database\Type\TypeConfigurator;

/** @var TypeConfigurator $databaseTypeConfigurator */
$databaseTypeConfigurator->addType(RoleType::class, StringType::class);
$databaseTypeConfigurator->addType(StatusType::class, StringType::class);
