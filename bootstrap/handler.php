<?php
declare(strict_types=1);

namespace KiwiSuite\Admin;

/** @var \KiwiSuite\ServiceManager\ServiceManagerConfigurator $handlerConfigurator */
use KiwiSuite\CommandBus\Handler\HandlerInterface;

$handlerConfigurator->addDirectory( __DIR__ . '/../src/Handler/', true, [HandlerInterface::class]);

