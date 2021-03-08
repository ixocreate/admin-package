<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin;

use Ixocreate\Admin\Console\CreateUserCommand;
use Ixocreate\Admin\Console\ResetPasswordCommand;
use Ixocreate\Application\Console\ConsoleConfigurator;

/** @var ConsoleConfigurator $console */
$console->addCommand(CreateUserCommand::class);
$console->addCommand(ResetPasswordCommand::class);
