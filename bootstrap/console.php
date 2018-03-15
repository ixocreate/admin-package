<?php
namespace KiwiSuite\Admin;

/** @var ConsoleConfigurator $console */
use KiwiSuite\ApplicationConsole\Command\CommandInterface;
use KiwiSuite\ApplicationConsole\ConsoleConfigurator;
use KiwiSuite\ServiceManager\ServiceManagerConfigurator;

$console->addDirectory(__DIR__ . '/../src/Console', true);
