<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Package\Config\Navigation;

class SubItem
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $permissions;

    /**
     * @var string
     */
    private $icon;

    /**
     * @var string
     */
    private $url;

    /**
     * @var int
     */
    private $priority;

    /**
     * Item constructor.
     * @param string $name
     * @param array $permissions
     * @param string $icon
     * @param string $url
     * @param int $priority
     */
    public function __construct(string $name, array $permissions, string $icon, string $url, int $priority)
    {
        $this->name = $name;
        $this->permissions = $permissions;
        $this->icon = $icon;
        $this->url = $url;
        $this->priority = $priority;
    }

    /**
     * @return string
     */
    final public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    final public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    final public function getPermissions(): array
    {
        return $this->permissions;
    }

    /**
     * @param array $permissions
     */
    final public function setPermissions(array $permissions): void
    {
        $this->permissions = $permissions;
    }

    /**
     * @return string
     */
    final public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     */
    final public function setIcon(string $icon): void
    {
        $this->icon = $icon;
    }

    /**
     * @return string
     */
    final public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    final public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return int
     */
    final public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     */
    final public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }

    public function toArray(): array
    {
        return [
            'name'  => $this->name,
            'icon'  => $this->icon,
            'url'   => $this->url,
            'permissions' => $this->permissions,
        ];
    }
}
