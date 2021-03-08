<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin;

use Ixocreate\Admin\Command\Account;
use Ixocreate\Admin\Command\User;
use Ixocreate\CommandBus\CommandBusConfigurator;

/** @var CommandBusConfigurator $commandBus */
$commandBus->addCommand(Account\ChangeAttributesCommand::class);
$commandBus->addCommand(Account\ChangeEmailCommand::class);
$commandBus->addCommand(Account\ChangeLocaleCommand::class);
$commandBus->addCommand(Account\ChangePasswordCommand::class);
$commandBus->addCommand(User\ChangePasswordCommand::class);
$commandBus->addCommand(User\CreateUserCommand::class);
$commandBus->addCommand(User\UpdateUserCommand::class);
