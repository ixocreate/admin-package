<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Bootstrap;

use Ixocreate\Application\Service\Bootstrap\BootstrapItemInterface;
use Ixocreate\Application\Service\Configurator\ConfiguratorInterface;
use Ixocreate\Admin\Config\AdminConfigurator;

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
