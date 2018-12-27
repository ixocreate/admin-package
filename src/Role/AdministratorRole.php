<?php
/**
 * kiwi-suite/admin (https://github.com/kiwi-suite/admin)
 *
 * @package kiwi-suite/admin
 * @link https://github.com/kiwi-suite/admin
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Role;

use Ixocreate\Contract\Admin\RoleInterface;

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
