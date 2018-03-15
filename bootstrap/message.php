<?php
declare(strict_types=1);

namespace KiwiSuite\Admin;

/** @var MessageConfigurator $message */
use KiwiSuite\CommandBus\Message\MessageConfigurator;

$message->addDirectory( __DIR__ . '/../src/Message/', true);

