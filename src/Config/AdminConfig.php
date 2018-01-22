<?php
namespace KiwiSuite\Admin\Config;

use Psr\Http\Message\UriInterface;

final class AdminConfig
{
    /**
     * @var UriInterface
     */
    private $uri;

    /**
     * @var string
     */
    private $apiBasePath;

    /**
     * @var array
     */
    private $project;

    public function __construct(
        UriInterface $uri,
        string $apiBasePath,
        array $project
    ) {
        $this->uri = $uri;
        $this->apiBasePath = '/' . trim($apiBasePath, '/');
        $this->project = $project;
    }

    /**
     * @return UriInterface
     */
    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    /**
     * @return string
     */
    public function getApiBasePath(): string
    {
        return $this->apiBasePath;
    }

    /**
     * @return array
     */
    public function getProject(): array
    {
        return $this->project;
    }
}
