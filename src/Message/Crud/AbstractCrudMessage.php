<?php
namespace KiwiSuite\Admin\Message\Crud;

use Doctrine\Instantiator\Instantiator;
use KiwiSuite\Admin\Message\CrudMessageInterface;
use KiwiSuite\Admin\Message\CrudMessageTrait;
use KiwiSuite\Admin\Resource\ResourceInterface;
use KiwiSuite\Admin\Resource\ResourceSubManager;
use KiwiSuite\CommandBus\Message\MessageInterface;
use KiwiSuite\CommandBus\Message\MessageTrait;
use KiwiSuite\CommandBus\Message\Validation\Result;
use KiwiSuite\Database\Repository\Factory\RepositorySubManager;
use KiwiSuite\Database\Repository\RepositoryInterface;
use KiwiSuite\Entity\Entity\DefinitionCollection;
use KiwiSuite\Entity\Entity\EntityInterface;

abstract class AbstractCrudMessage implements CrudMessageInterface
{
    use CrudMessageTrait;

    /**
     * @var ResourceSubManager
     */
    protected $resourceSubManager;
    /**
     * @var RepositorySubManager
     */
    protected $repositorySubManager;

    /**
     * AbstractCrudMessage constructor.
     * @param ResourceSubManager $resourceSubManager
     * @param RepositorySubManager $repositorySubManager
     */
    public function __construct(ResourceSubManager $resourceSubManager, RepositorySubManager $repositorySubManager)
    {
        $this->resourceSubManager = $resourceSubManager;
        $this->repositorySubManager = $repositorySubManager;
    }

    /**
     * @return ResourceInterface
     */
    protected function resource(): ResourceInterface
    {
        return $this->resourceSubManager->get($this->metadata[ResourceInterface::class]);
    }

    /**
     * @return RepositoryInterface
     */
    protected function repository(): RepositoryInterface
    {
        return $this->repositorySubManager->get($this->resource()->repository());
    }

    /**
     * @return DefinitionCollection
     */
    protected function entityDefinitions(): DefinitionCollection
    {
        return (new Instantiator())->instantiate($this->repository()->getEntityName())->getDefinitions();
    }

    protected function applyData(EntityInterface $entity): EntityInterface
    {
        foreach ($this->data as $key => $value) {
            $entity = $entity->with($key, $value);
        }

        return $entity;
    }

    /**
     * @param Result $result
     */
    protected function doValidate(Result $result): void
    {
        if (empty($this->metadata[ResourceInterface::class])) {
            $result->addError("resource_not_set");
            return;
        }

        if (!$this->resourceSubManager->has($this->metadata[ResourceInterface::class])) {
            $result->addError("invalid_resource");
            return;
        }
    }
}
