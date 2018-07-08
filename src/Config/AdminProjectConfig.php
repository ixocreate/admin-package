<?php
/**
 * kiwi-suite/admin (https://github.com/kiwi-suite/admin)
 *
 * @package kiwi-suite/admin
 * @see https://github.com/kiwi-suite/admin
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace KiwiSuite\Admin\Config;

use KiwiSuite\Contract\Application\SerializableServiceInterface;

final class AdminProjectConfig implements SerializableServiceInterface
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
        'navigation'    => [],
    ];

    /**
     * AdminConfig constructor.
     * @param AdminConfigurator $adminConfigurator
     */
    public function __construct(AdminConfigurator $adminConfigurator)
    {
        $this->config = $adminConfigurator->toArray();
    }

    /**
     * @return string
     */
    public function author(): string
    {
        return $this->config['author'];
    }

    /**
     * @return string
     */
    public function copyright(): string
    {
        return $this->config['copyright'];
    }

    /**
     * @return string
     */
    public function description(): string
    {
        return $this->config['description'];
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->config['name'];
    }

    /**
     * @return bool
     */
    public function poweredBy(): bool
    {
        return $this->config['poweredBy'];
    }

    /**
     * @return string
     */
    public function logo(): string
    {
        return $this->config['logo'];
    }

    /**
     * @return string
     */
    public function icon(): string
    {
        return $this->config['icon'];
    }

    /**
     * @return string
     */
    public function background(): string
    {
        return $this->config['background'];
    }

    /**
     * @return array
     */
    public function navigation(): array
    {
        return $this->config['navigation'];
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize($this->config);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $this->config = unserialize($serialized);
    }
}
