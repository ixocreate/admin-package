<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Package\Admin\Config\Client;

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
