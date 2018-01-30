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

use Psr\Http\Message\UriInterface;

final class AdminConfig
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var array
     */
    private $project;

    /**
     * @var UriInterface
     */
    private $uri;

    /**
     * AdminConfig constructor.
     * @param array $config
     * @param UriInterface $uri
     */
    public function __construct(array $config, UriInterface $uri)
    {
        $this->config = $config;
        $this->uri = $uri;
    }

    /**
     * @return UriInterface
     */
    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    /**
     * @return array
     */
    public function getProject(): array
    {
        return $this->config['project'];
    }

    /**
     * @param $requestHost
     * @return string
     */
    public function getSessionDomain(string $requestHost)
    {
        if (empty($this->config['security']['domain'])) {
            return $requestHost;
        }
        return $this->config['security']['domain'];
    }
}
