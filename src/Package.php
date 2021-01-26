<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin;

use Ixocreate\Admin\Permission\Permission;
use Ixocreate\Admin\Permission\Voter\VoterSubManager;
use Ixocreate\Application\Package\BootInterface;
use Ixocreate\Application\Package\PackageInterface;
use Ixocreate\ServiceManager\ServiceManagerInterface;

class Package implements PackageInterface, BootInterface
{
    /**
     * @return array
     */
    public function getBootstrapItems(): array
    {
        return [
            AdminBootstrapItem::class,
        ];
    }

    /**
     * @param ServiceManagerInterface $serviceManager
     */
    public function boot(ServiceManagerInterface $serviceManager): void
    {
        Permission::initialize($serviceManager->get(VoterSubManager::class));
    }

    /**
     * @return null|string
     */
    public function getBootstrapDirectory(): ?string
    {
        return __DIR__ . '/../bootstrap';
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            \Ixocreate\Media\Package::class,
            \Ixocreate\Cms\Package::class,
            \Ixocreate\Intl\Package::class,
        ];
    }
}
