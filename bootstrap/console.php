<?php
namespace KiwiSuite\Admin;

/** @var ServiceManagerConfigurator $consoleServiceManagerConfigurator */
use KiwiSuite\ApplicationConsole\Command\CommandInterface;
use KiwiSuite\ServiceManager\ServiceManagerConfigurator;

$consoleServiceManagerConfigurator->addDirectory(__DIR__ . '/../src/Console', true, [CommandInterface::class]);
