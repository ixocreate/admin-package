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
     * @var array
     */
    private $project;

    public function __construct(
        UriInterface $uri,
        array $project
    ) {
        $this->uri = $uri;
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
     * @return array
     */
    public function getProject(): array
    {
        return $this->project;
    }
}
