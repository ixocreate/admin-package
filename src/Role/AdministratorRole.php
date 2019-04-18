<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Package\Role;

use Ixocreate\Admin\Package\RoleInterface;

final class AdministratorRole implements RoleInterface
{
    /**
     * @return string
     */
    public static function serviceName(): string
    {
        return 'admin';
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return 'Administrator';
    }

    /**
     * @return array
     */
    public function getPermissions(): array
    {
        return [
            '*',
        ];
    }
}
