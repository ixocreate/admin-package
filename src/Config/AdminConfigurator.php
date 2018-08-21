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

namespace KiwiSuite\Admin\Config;

use KiwiSuite\Admin\Config\Navigation\Group;
use KiwiSuite\Contract\Application\ConfiguratorInterface;
use KiwiSuite\Contract\Application\ServiceRegistryInterface;
use Zend\Stdlib\SplPriorityQueue;

final class AdminConfigurator implements ConfiguratorInterface
{
    private $config = [
        'author'        => '',
        'copyright'     => '',
        'description'   => '',
        'name'          => '',
        'poweredBy'     => true,
        'logo'          => '',
        'icon'          => '',
        'background'    => '',
        'adminBuildPath'=> __DIR__ . '/../../../admin-frontend/build/',
    ];

    /**
     * @var Group[]
     */
    private $navigation = [];


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

    public function setAdminBuildPath(string $buildPath): void
    {
        $this->config['adminBuildPath'] = $buildPath;
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
    }
}
