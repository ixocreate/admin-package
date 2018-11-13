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

namespace KiwiSuite\Admin\Config\Client\Provider;

use KiwiSuite\Admin\Config\AdminConfig;
use KiwiSuite\Admin\Permission\PermissionTemp;
use KiwiSuite\Contract\Admin\ClientConfigProviderInterface;
use KiwiSuite\Contract\Admin\RoleInterface;

final class NavigationProvider implements ClientConfigProviderInterface
{
    /**
     * @var AdminConfig
     */
    private $adminConfig;

    public function __construct(AdminConfig $adminConfig)
    {
        $this->adminConfig = $adminConfig;
    }

    /**
     * @param RoleInterface|null $role
     * @return array
     */
    public function clientConfig(?RoleInterface $role = null): array
    {
        if (empty($role)) {
            return [];
        }

        $permission = new PermissionTemp($role);

        $navigation = [];

        foreach ($this->adminConfig->navigation() as $navigationEntry) {
            $children = [];
            foreach ($navigationEntry['children'] as $child) {
                foreach ($child['permissions'] as $permissionItem) {
                    if (!$permission->can($permissionItem)) {
                        continue 2;
                    }
                }
                $children[] = $child;
            }

            if (\count($children) === 0) {
                continue;
            }

            $navigationEntry['children'] = $children;
            $navigation[] = $navigationEntry;
        }

        return $navigation;
    }

    public static function serviceName(): string
    {
        return 'navigation';
    }
}
