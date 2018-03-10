<?php
namespace KiwiSuite\Admin\Message;

use KiwiSuite\CommandBus\Message\MessageTrait;
use KiwiSuite\Entity\Entity\EntityInterface;

trait CrudMessageTrait
{
    /**
     *
     */
    use MessageTrait;

    /**
     * @var EntityInterface;
     */
    private $entity;

    /**
     * @return EntityInterface
     */
    public function entity(): EntityInterface
    {
        if ($this->entity === null) {
            $this->entity = $this->fetchEntity();
        }

        return $this->entity;
    }

    /**
     * @return EntityInterface
     */
    abstract public function fetchEntity(): EntityInterface;

    /**
     * @param EntityInterface $entity
     * @return CrudMessageInterface
     */
    public function withEntity(EntityInterface $entity): CrudMessageInterface
    {
        $message = clone $this;
        $message = $message->inject($this->data(), $this->metadata(), $this->uuid(), $this->createdAt());
        $message->entity = $entity;

        return $message;
    }
}
