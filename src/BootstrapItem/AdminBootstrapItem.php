<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\BootstrapItem;

use Ixocreate\Admin\Config\AdminConfigurator;
use Ixocreate\Contract\Application\BootstrapItemInterface;
use Ixocreate\Contract\Application\ConfiguratorInterface;

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
