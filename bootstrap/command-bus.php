<?php
declare(strict_types=1);

namespace Ixocreate\Admin\Package;

/** @var \Ixocreate\CommandBus\Package\Configurator $commandBus */

$commandBus->addCommandDirectory(__DIR__ . '/../src/Command', true);
