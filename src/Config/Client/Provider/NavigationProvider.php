<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Config\Client\Provider;

use Ixocreate\Admin\Config\AdminConfig;
use Ixocreate\Admin\Permission\Permission;
use Ixocreate\Contract\Admin\ClientConfigProviderInterface;
use Ixocreate\Contract\Admin\UserInterface;

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
     * @return string
     */
    public static function serviceName(): string
    {
        return 'navigation';
    }

    /**
     * @param UserInterface|null $user
     * @return array
     */
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
