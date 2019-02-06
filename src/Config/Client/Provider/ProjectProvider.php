<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Config\Client\Provider;

use Ixocreate\Admin\Config\AdminConfig;
use Ixocreate\Contract\Admin\ClientConfigProviderInterface;
use Ixocreate\Contract\Admin\RoleInterface;
use Ixocreate\Contract\Admin\UserInterface;

final class ProjectProvider implements ClientConfigProviderInterface
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
        return 'project';
    }

    /**
     * @param UserInterface|null $user
     * @return array
     */
    public function clientConfig(?UserInterface $user = null): array
    {
        return [
            'author' => $this->adminConfig->author(),
            'name' => $this->adminConfig->name(),
            'poweredBy' => $this->adminConfig->poweredBy(),
            'copyright' => $this->adminConfig->copyright(),
            'description' => $this->adminConfig->description(),
            'background' => $this->adminConfig->background(),
            'loginMessage' => $this->adminConfig->loginMessage(),
            'icon' => $this->adminConfig->icon(),
            'logo' => $this->adminConfig->logo(),
        ];
    }
}
