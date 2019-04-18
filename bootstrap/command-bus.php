<?php
declare(strict_types=1);

namespace Ixocreate\Package\Admin;

/** @var \Ixocreate\Package\CommandBus\Configurator $commandBus */

$commandBus->addCommandDirectory(__DIR__ . '/../src/Command', true);
