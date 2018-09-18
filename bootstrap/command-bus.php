<?php
namespace KiwiSuite\Admin;

/** @var \KiwiSuite\CommandBus\Configurator $commandBus */
$commandBus->addCommandDirectory(__DIR__ . '/../src/Command', true);
