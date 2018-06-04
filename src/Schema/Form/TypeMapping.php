<?php

declare(strict_types=1);
namespace KiwiSuite\Admin\Schema\Form;

use KiwiSuite\Contract\Application\SerializableServiceInterface;

final class TypeMapping implements SerializableServiceInterface
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

    public function getTypeByElement(string $element): ?string
    {
        $type = array_search($element, $this->mapping);
        if (empty($type)) {
            return null;
        }
        return $this->getElementByType($type);
    }

    public function getElementByType(string $type): ?string
    {
        if (empty($this->mapping[$type])) {
            return null;
        }

        return $this->mapping[$type];
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
