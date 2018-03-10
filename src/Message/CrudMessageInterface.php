<?php
namespace KiwiSuite\Admin\Message;

use KiwiSuite\CommandBus\Message\MessageInterface;
use KiwiSuite\Entity\Entity\EntityInterface;

interface CrudMessageInterface extends MessageInterface
{
    public function fetchEntity(): EntityInterface;

    public function entity(): EntityInterface;

    public function withEntity(EntityInterface $entity): CrudMessageInterface;
}
