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

namespace KiwiSuite\Admin\Handler\Crud;

use KiwiSuite\Admin\Message\CrudMessageInterface;
use KiwiSuite\CommandBus\Handler\HandlerInterface;
use KiwiSuite\CommandBus\Message\MessageInterface;
use KiwiSuite\Database\Repository\EntityRepositoryMapping;
use KiwiSuite\Database\Repository\Factory\RepositorySubManager;
use KiwiSuite\Database\Repository\RepositoryInterface;
use KiwiSuite\Entity\Entity\EntityInterface;

final class DeleteHandler implements HandlerInterface
{
    /**
     * @var RepositorySubManager
     */
    private $repositorySubManager;
    /**
     * @var EntityRepositoryMapping
     */
    private $entityRepositoryMapping;

    /**
     * EditHandler constructor.
     * @param RepositorySubManager $repositorySubManager
     * @param EntityRepositoryMapping $entityRepositoryMapping
     */
    public function __construct(RepositorySubManager $repositorySubManager, EntityRepositoryMapping $entityRepositoryMapping)
    {
        $this->repositorySubManager = $repositorySubManager;
        $this->entityRepositoryMapping = $entityRepositoryMapping;
    }

    /**
     * @param MessageInterface $message
     * @throws \Exception
     * @return MessageInterface
     */
    public function __invoke(MessageInterface $message): MessageInterface
    {
        if (!($message instanceof CrudMessageInterface)) {
            throw new \Exception("invalid message");
        }

        /** @var EntityInterface $entity */
        $entity = $message->entity();
        $repositoryName = $this->entityRepositoryMapping->getRepositoryByEntity(\get_class($entity));

        /** @var RepositoryInterface $repository */
        $repository = $this->repositorySubManager->get($repositoryName);

        $repository->remove($entity);

        return $message;
    }
}
