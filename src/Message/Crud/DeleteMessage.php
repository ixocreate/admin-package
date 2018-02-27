<?php
namespace KiwiSuite\Admin\Message\Crud;

use KiwiSuite\Admin\Entity\User;
use KiwiSuite\Admin\Handler\Crud\UpdateHandler;
use KiwiSuite\Admin\Message\CrudMessageInterface;
use KiwiSuite\Admin\Resource\ResourceInterface;
use KiwiSuite\Admin\Resource\ResourceSubManager;
use KiwiSuite\CommandBus\Message\MessageInterface;
use KiwiSuite\CommandBus\Message\MessageTrait;
use KiwiSuite\CommandBus\Message\Validation\Result;
use KiwiSuite\CommonTypes\Entity\EmailType;
use KiwiSuite\Database\Repository\Factory\RepositorySubManager;
use KiwiSuite\Database\Repository\RepositoryInterface;
use KiwiSuite\Entity\Entity\EntityInterface;
use KiwiSuite\Entity\Type\Type;

final class UpdateMessage implements MessageInterface, CrudMessageInterface
{
    use MessageTrait;

    /**
     * @var ResourceSubManager
     */
    private $resourceSubManager;
    /**
     * @var RepositorySubManager
     */
    private $repositorySubManager;

    public function __construct(ResourceSubManager $resourceSubManager, RepositorySubManager $repositorySubManager)
    {
        $this->resourceSubManager = $resourceSubManager;
        $this->repositorySubManager = $repositorySubManager;
    }

    /**
     * @return string
     */
    public static function getHandler(): string
    {
        return UpdateHandler::class;
    }

    public function fetchEntity(): EntityInterface
    {
        /** @var User $user */
        $entity = $this->userRepository->findOneBy(['id' => $this->metadata[User::class]]);
        return $user->with('email', $this->email());
    }

    private function getRepository(): RepositoryInterface
    {
        /** @var ResourceInterface $resource */
        $resource = $this->resourceSubManager->get($this->metadata[ResourceInterface::class]);

        return $this->repositorySubManager->get($resource->repository());
    }

    private function getEntity()
    {
        return $this->getRepository()->findOneBy(['id' => $this->metadata['id']]);
    }

    /**
     * @param Result $result
     * @throws \Assert\AssertionFailedException
     */
    protected function doValidate(Result $result): void
    {
        /** @var EntityInterface $entity */
        $entity = $this->getEntity();
        if (empty($entity)) {
            $result->addError("invalid_id");
            return;
        }

        foreach ($this->data as $key => $value) {
            try {
                $entity->with($key, $value);
            } catch (\Exception $e) {
                $result->addError("invalid_" . $key);
            }
        }
    }
}
