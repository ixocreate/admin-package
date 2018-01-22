<?php
declare(strict_types=1);

namespace KiwiSuite\Admin;

/** @var \KiwiSuite\ServiceManager\ServiceManagerConfigurator $serviceManagerConfigurator */
use KiwiSuite\Admin\Config\AdminConfig;
use KiwiSuite\Admin\Config\Factory\AdminConfigFactory;

$serviceManagerConfigurator->addFactory(AdminConfig::class, AdminConfigFactory::class);
