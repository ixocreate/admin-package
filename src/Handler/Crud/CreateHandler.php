<?php
namespace KiwiSuite\Admin\Handler\Crud;

use KiwiSuite\Admin\Message\CrudMessageInterface;
use KiwiSuite\CommandBus\Handler\HandlerInterface;
use KiwiSuite\CommandBus\Message\MessageInterface;
use KiwiSuite\Database\Repository\EntityRepositoryMapping;
use KiwiSuite\Database\Repository\Factory\RepositorySubManager;
use KiwiSuite\Database\Repository\RepositoryInterface;
use KiwiSuite\Entity\Entity\EntityInterface;

final class CreateHandler implements HandlerInterface
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
     */
    public function __invoke(MessageInterface $message)
    {
        if (!($message instanceof CrudMessageInterface)) {
            throw new \Exception("invalid message");
        }
        /** @var EntityInterface $entity */
        $entity = $message->fetchEntity();
        $repositoryName = $this->entityRepositoryMapping->getRepositoryByEntity(get_class($entity));

        /** @var RepositoryInterface $repository */
        $repository = $this->repositorySubManager->get($repositoryName);

        $repository->save($entity);
    }
}
