<?php
declare(strict_types=1);

namespace Ixocreate\Admin\Package;

use Ixocreate\Application\Console\ConsoleConfigurator;

/** @var ConsoleConfigurator $console */

$console->addDirectory(__DIR__ . '/../src/Console', true);
