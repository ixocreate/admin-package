<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Permission;

use Ixocreate\Admin\RoleInterface;

final class PermissionTemp
{
    /**
     * @var RoleInterface
     */
    private $role;

    public function __construct(RoleInterface $role)
    {
        $this->role = $role;
    }

    public function can(string $permission): bool
    {
        if (\in_array($permission, $this->role->getPermissions())) {
            return true;
        }

        if (\in_array("*", $this->role->getPermissions())) {
            return true;
        }

        $permissionParts = \explode('.', $permission);

        for ($i = 0; $i < \count($permissionParts); $i++) {
            $checkPermission = [];
            for ($j = 0; $j <= $i; $j++) {
                $checkPermission[] = $permissionParts[$j];
                if (\in_array(\implode('.', $checkPermission), $this->role->getPermissions())) {
                    return true;
                }
                if (\in_array(\implode('.', $checkPermission) . '.*', $this->role->getPermissions())) {
                    return true;
                }
            }
        }

        return false;
    }
}
