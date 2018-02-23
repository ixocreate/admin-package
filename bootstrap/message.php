<?php
declare(strict_types=1);

namespace KiwiSuite\Admin;

/** @var \KiwiSuite\ServiceManager\ServiceManagerConfigurator $messageConfigurator */
use KiwiSuite\CommandBus\Message\MessageInterface;

$messageConfigurator->addDirectory( __DIR__ . '/../src/Message/', true, [MessageInterface::class]);

