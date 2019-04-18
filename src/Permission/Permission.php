<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Package\Permission;

use Ixocreate\Admin\Package\Entity\User;

final class Permission
{
    /**
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function withUser(User $user): Permission
    {
        return new Permission($user);
    }

    public function can(string $permission): bool
    {
        $role = $this->user->role()->getRole();

        if (\in_array($permission, $role->getPermissions())) {
            return true;
        }

        if (\in_array("*", $role->getPermissions())) {
            return true;
        }

        $permissionParts = \explode('.', $permission);

        for ($i = 0; $i < \count($permissionParts); $i++) {
            $checkPermission = [];
            for ($j = 0; $j <= $i; $j++) {
                $checkPermission[] = $permissionParts[$j];
                if (\in_array(\implode('.', $checkPermission), $role->getPermissions())) {
                    return true;
                }
                if (\in_array(\implode('.', $checkPermission) . '.*', $role->getPermissions())) {
                    return true;
                }
            }
        }

        return false;
    }
}
