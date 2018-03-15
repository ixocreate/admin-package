<?php
/**
 * kiwi-suite/admin (https://github.com/kiwi-suite/admin)
 *
 * @package kiwi-suite/admin
 * @see https://github.com/kiwi-suite/admin
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace KiwiSuite\Admin\BootstrapItem;

use KiwiSuite\Admin\Pipe\PipeConfigurator;
use KiwiSuite\Contract\Application\BootstrapItemInterface;
use KiwiSuite\Contract\Application\ConfiguratorInterface;

final class PipeBootstrapItem implements BootstrapItemInterface
{
    /**
     * @return mixed
     */
    public function getConfigurator(): ConfiguratorInterface
    {
        return new PipeConfigurator();
    }

    /**
     * @return string
     */
    public function getVariableName(): string
    {
        return 'pipe';
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return 'admin_pipe.php';
    }
}
