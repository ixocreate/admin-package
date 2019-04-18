<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Package\Config\Client\Provider;

use Ixocreate\Admin\Package\Config\AdminConfig;
use Ixocreate\Admin\Package\ClientConfigProviderInterface;
use Ixocreate\Admin\Package\UserInterface;

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
            'loginUrl' => (string) $this->adminConfig->uri() . '/login',
            'logoutUrl' => (string) $this->adminConfig->uri() . '/logout',
        ];
    }
}
