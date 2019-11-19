<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Config\Client\Provider;

use Ixocreate\Admin\ClientConfigProviderInterface;
use Ixocreate\Admin\Config\AdminConfig;
use Ixocreate\Admin\Permission\Permission;
use Ixocreate\Admin\UserInterface;

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

    public static function serviceName(): string
    {
        return 'navigation';
    }

    public function clientConfig(?UserInterface $user = null): array
    {
        if (empty($user)) {
            return [];
        }

        $permission = new Permission($user);

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
}
