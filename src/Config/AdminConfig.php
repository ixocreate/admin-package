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

use KiwiSuite\Asset\Asset;
use KiwiSuite\Contract\Http\SegmentProviderInterface;
use Psr\Http\Message\UriInterface;

final class AdminConfig implements SegmentProviderInterface
{
    /**
     * @var UriInterface
     */
    private $uri;
    /**
     * @var AdminProjectConfig
     */
    private $adminProjectConfig;
    /**
     * @var Asset
     */
    private $asset;

    /**
     * AdminConfig constructor.
     * @param AdminProjectConfig $adminProjectConfig
     * @param UriInterface $uri
     */
    public function __construct(AdminProjectConfig $adminProjectConfig, UriInterface $uri, Asset $asset)
    {
        $this->uri = $uri;
        $this->adminProjectConfig = $adminProjectConfig;
        $this->asset = $asset;
    }

    public function author(): string
    {
        return $this->adminProjectConfig->author();
    }

    /**
     * @return string
     */
    public function copyright(): string
    {
        return $this->adminProjectConfig->copyright();
    }

    /**
     * @return string
     */
    public function description(): string
    {
        return $this->adminProjectConfig->description();
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->adminProjectConfig->name();
    }

    /**
     * @return bool
     */
    public function poweredBy(): bool
    {
        return $this->adminProjectConfig->poweredBy();
    }

    /**
     * @return string
     */
    public function logo(): string
    {
        return $this->asset->getUrl($this->adminProjectConfig->logo());
    }

    /**
     * @return string
     */
    public function icon(): string
    {
        return $this->asset->getUrl($this->adminProjectConfig->icon());
    }

    /**
     * @return string
     */
    public function background(): string
    {
        return $this->asset->getUrl($this->adminProjectConfig->background());
    }

    /**
     * @return string
     */
    public function adminBuildPath(): string
    {
        return $this->adminProjectConfig->adminBuildPath();
    }

    /**
     * @return array
     */
    public function adminBuildFiles(): array
    {
        return $this->adminProjectConfig->adminBuildFiles();
    }

    /**
     * @return string
     */
    public function userAttributesSchema(): ?string
    {
        return $this->adminProjectConfig->userAttributesSchema();
    }

    /**
     * @return string
     */
    public function accountAttributesSchema(): ?string
    {
        return $this->adminProjectConfig->accountAttributesSchema();
    }

    /**
     * @return UriInterface
     * @deprecated
     */
    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    /**
     * @return UriInterface
     */
    public function uri(): UriInterface
    {
        return $this->uri;
    }

    /**
     * @return array
     */
    public function navigation(): array
    {
        return $this->adminProjectConfig->navigation();
    }

    /**
     * @return string
     */
    public function getSegment(): string
    {
        return (string) $this->uri();
    }
}
