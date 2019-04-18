<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Package\BootstrapItem;

use Ixocreate\Application\Bootstrap\BootstrapItemInterface;
use Ixocreate\Application\ConfiguratorInterface;
use Ixocreate\Admin\Package\Config\AdminConfigurator;

final class AdminBootstrapItem implements BootstrapItemInterface
{
    /**
     * @return mixed
     */
    public function getConfigurator(): ConfiguratorInterface
    {
        return new AdminConfigurator();
    }

    /**
     * @return string
     */
    public function getVariableName(): string
    {
        return 'admin';
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return 'admin.php';
    }
}
