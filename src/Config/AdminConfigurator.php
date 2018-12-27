<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Config;

use Ixocreate\Admin\Config\Client\ClientConfigProviderSubManager;
use Ixocreate\Admin\Config\Navigation\Group;
use Ixocreate\Admin\Dashboard\DashboardWidgetProviderSubManager;
use Ixocreate\Admin\Role\RoleSubManager;
use Ixocreate\Contract\Admin\ClientConfigProviderInterface;
use Ixocreate\Contract\Admin\DashboardWidgetProviderInterface;
use Ixocreate\Contract\Application\ConfiguratorInterface;
use Ixocreate\Contract\Application\ServiceRegistryInterface;
use Ixocreate\Contract\Schema\AdditionalSchemaInterface;
use Ixocreate\Schema\AdditionalSchema\AdditionalSchemaSubManager;
use Ixocreate\ServiceManager\Factory\AutowireFactory;
use Ixocreate\ServiceManager\SubManager\SubManagerConfigurator;
use Zend\Stdlib\SplPriorityQueue;

final class AdminConfigurator implements ConfiguratorInterface
{
    private $config = [
        'author' => '',
        'copyright' => '',
        'description' => '',
        'name' => '',
        'poweredBy' => true,
        'logo' => '',
        'icon' => '',
        'background' => '',
        'loginMessage' => '',
        'clientConfigProvider' => [],
        'adminBuildPath' => __DIR__ . '/../../../admin-frontend/build/',
        'userAttributesSchema' => null,
        'accountAttributesSchema' => null,
    ];

    /**
     * @var Group[]
     */
    private $navigation = [];

    /**
     * @var SubManagerConfigurator
     */
    private $clientSubManagerConfigurator;

    /**
     * @var SubManagerConfigurator
     */
    private $roleSubManagerConfigurator;

    /**
     * @var SubManagerConfigurator
     */
    private $dashboardWidgetSubManagerConfigurator;

    /**
     * @var SubManagerConfigurator
     */
    private $additionalSchemaSubManagerConfigurator;

    /**
     * AdminConfigurator constructor.
     */
    public function __construct()
    {
        $this->clientSubManagerConfigurator = new SubManagerConfigurator(
            ClientConfigProviderSubManager::class,
            ClientConfigProviderInterface::class
        );
        $this->roleSubManagerConfigurator = new SubManagerConfigurator(
            RoleSubManager::class,
            \Ixocreate\Contract\Admin\RoleInterface::class
        );
        $this->dashboardWidgetSubManagerConfigurator = new SubManagerConfigurator(
            DashboardWidgetProviderSubManager::class,
            DashboardWidgetProviderInterface::class
        );
        $this->additionalSchemaSubManagerConfigurator = new SubManagerConfigurator(
            AdditionalSchemaSubManager::class,
            AdditionalSchemaInterface::class
        );
    }

    /**
     * @param string $author
     */
    public function setAuthor(string $author): void
    {
        $this->config['author'] = $author;
    }

    /**
     * @param string $copyright
     */
    public function setCopyright(string $copyright): void
    {
        $this->config['copyright'] = $copyright;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->config['description'] = $description;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->config['name'] = $name;
    }

    /**
     * @param bool $poweredBy
     */
    public function setPoweredBy(bool $poweredBy): void
    {
        $this->config['poweredBy'] = $poweredBy;
    }

    /**
     * @param string $logo
     */
    public function setLogo(string $logo): void
    {
        $this->config['logo'] = $logo;
    }

    /**
     * @param string $icon
     */
    public function setIcon(string $icon): void
    {
        $this->config['icon'] = $icon;
    }

    /**
     * @param string $background
     */
    public function setBackground(string $background): void
    {
        $this->config['background'] = $background;
    }

    /**
     * @param string $buildPath
     */
    public function setAdminBuildPath(string $buildPath): void
    {
        $this->config['adminBuildPath'] = $buildPath;
    }

    /**
     * @param string $userAttributesSchema
     * @param string $factory
     */
    public function addUserAttributesSchema(string $userAttributesSchema, string $factory = AutowireFactory::class): void
    {
        $this->config['userAttributesSchema'] = $userAttributesSchema;
        $this->additionalSchemaSubManagerConfigurator->addFactory($userAttributesSchema, $factory);
    }

    /**
     * @param string $accountAttributesSchema
     * @param string $factory
     */
    public function addAccountAttributesSchema(string $accountAttributesSchema, string $factory = AutowireFactory::class): void
    {
        $this->config['accountAttributesSchema'] = $accountAttributesSchema;
        $this->additionalSchemaSubManagerConfigurator->addFactory($accountAttributesSchema, $factory);
    }

    /**
     * @param string $clientProvider
     * @param string $factory
     */
    public function addClientProvider(string $clientProvider, string $factory = AutowireFactory::class): void
    {
        $this->config['clientConfigProvider'][] = $clientProvider;
        $this->clientSubManagerConfigurator->addFactory($clientProvider, $factory);
    }

    /**
     * @param string $directory
     * @param bool $recursive
     */
    public function addRoleDirectory(string $directory, bool $recursive = true): void
    {
        $this->roleSubManagerConfigurator->addDirectory($directory, $recursive);
    }

    /**
     * @param string $role
     * @param string $factory
     */
    public function addRole(string $role, string $factory = AutowireFactory::class): void
    {
        $this->roleSubManagerConfigurator->addFactory($role, $factory);
    }

    /**
     * @param string $provider
     * @param string $factory
     */
    public function addDashboardProvider(string $provider, string $factory = AutowireFactory::class): void
    {
        $this->dashboardWidgetSubManagerConfigurator->addFactory($provider, $factory);
    }

    /**
     * @param string $directory
     * @param bool $recursive
     */
    public function addDashboardProviderDirectory(string $directory, bool $recursive = true): void
    {
        $this->dashboardWidgetSubManagerConfigurator->addDirectory($directory, $recursive);
    }

    /**
     * @param string $name
     * @param int $priority
     * @return Group
     */
    public function addNavigationGroup(string $name, int $priority = 0): Group
    {
        $item = new Group($name, $priority);
        $this->navigation[$item->getName()] = $item;

        return $item;
    }

    /**
     * @param Group $item
     */
    public function remove(Group $item): void
    {
        if (!\array_key_exists($item->getName(), $this->navigation)) {
            return;
        }

        unset($this->navigation[$item->getName()]);
    }

    /**
     * @param string $name
     * @return Group
     */
    public function getNavigationGroup(string $name): Group
    {
        return $this->navigation[$name];
    }

    /**
     * @param string $message
     */
    public function setLoginMessage(string $message): void
    {
        $this->config['loginMessage'] = $message;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $config = $this->config;
        $config['navigation'] = [];

        if (!empty($this->navigation)) {
            $queue = new SplPriorityQueue();
            foreach ($this->navigation as $group) {
                $queue->insert($group, $group->getPriority());
            }

            $queue->top();
            foreach ($queue as $group) {
                $config['navigation'][] = $group->toArray();
            }
        }

        return $config;
    }

    /**
     * @param ServiceRegistryInterface $serviceRegistry
     */
    public function registerService(ServiceRegistryInterface $serviceRegistry): void
    {
        $serviceRegistry->add(AdminProjectConfig::class, new AdminProjectConfig($this));
        $this->clientSubManagerConfigurator->registerService($serviceRegistry);
        $this->roleSubManagerConfigurator->registerService($serviceRegistry);
        $this->dashboardWidgetSubManagerConfigurator->registerService($serviceRegistry);
        $this->additionalSchemaSubManagerConfigurator->registerService($serviceRegistry);
    }
}
