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

namespace KiwiSuite\Admin\Config\Client;

final class ClientConfig implements \JsonSerializable
{
    /**
     * @var array
     */
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function config(): array
    {
        return $this->config;
    }

    public function hash(): string
    {
        return \sha1(\json_encode($this->config));
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->config;
    }
}
