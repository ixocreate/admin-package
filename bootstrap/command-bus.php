<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin;

use Ixocreate\CommandBus\CommandBusConfigurator;

/** @var CommandBusConfigurator $commandBus */
$commandBus->addCommandDirectory(__DIR__ . '/../src/Command', true);
