<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Config\Client;

use Ixocreate\Admin\ClientConfigProviderInterface;
use Ixocreate\Admin\Config\AdminProjectConfig;
use Ixocreate\Admin\UserInterface;

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
     * @param UserInterface|null $user
     * @return ClientConfig
     */
    public function generate(?UserInterface $user = null): ClientConfig
    {
        $config = [];

        foreach ($this->adminProjectConfig->clientConfigProvider() as $configProviderName) {
            /** @var ClientConfigProviderInterface $configProvider */
            $configProvider = $this->clientConfigProviderSubManager->get($configProviderName);
            $config[$configProvider::serviceName()] = $configProvider->clientConfig($user);
        }

        return new ClientConfig($config);
    }
}
