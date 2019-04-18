<?php
declare(strict_types=1);

namespace Ixocreate\Admin;

/** @var \Ixocreate\CommandBus\Configurator $commandBus */

$commandBus->addCommandDirectory(__DIR__ . '/../src/Command', true);
