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
}
