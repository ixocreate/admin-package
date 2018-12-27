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

namespace Ixocreate\Admin\Config\Client;

use Ixocreate\Admin\Config\AdminProjectConfig;
use Ixocreate\Contract\Admin\ClientConfigProviderInterface;
use Ixocreate\Contract\Admin\RoleInterface;

final class ClientConfigGenerator
{
    /**
     * @var AdminProjectConfig
     */
    private $adminProjectConfig;

    /**
     * @var ClientConfigProviderSubManager
     */
    private $clientConfigProviderSubManager;

    /**
     * ClientConfigGenerator constructor.
     * @param AdminProjectConfig $adminProjectConfig
     * @param ClientConfigProviderSubManager $clientConfigProviderSubManager
     */
    public function __construct(AdminProjectConfig $adminProjectConfig, ClientConfigProviderSubManager $clientConfigProviderSubManager)
    {
        $this->adminProjectConfig = $adminProjectConfig;
        $this->clientConfigProviderSubManager = $clientConfigProviderSubManager;
    }

    /**
     * @param RoleInterface $role
     * @return ClientConfig
     */
    public function generate(?RoleInterface $role = null): ClientConfig
    {
        $config = [];

        foreach ($this->adminProjectConfig->clientConfigProvider() as $configProviderName) {
            /** @var ClientConfigProviderInterface $configProvider */
            $configProvider = $this->clientConfigProviderSubManager->get($configProviderName);
            $config[$configProvider::serviceName()] = $configProvider->clientConfig($role);
        }

        return new ClientConfig($config);
    }
}
