<?php
namespace KiwiSuite\Admin\Resource;

use KiwiSuite\Contract\Application\SerializableServiceInterface;

final class ResourceMapping implements SerializableServiceInterface
{
    /**
     * @var array
     */
    private $mapping;

    public function __construct(array $mapping)
    {
        $this->mapping = $mapping;
    }

    public function getMapping(): array
    {
        return $this->mapping;
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return \serialize($this->mapping);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $this->mapping = \unserialize($serialized);
    }
}
