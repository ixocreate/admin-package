<?php
namespace KiwiSuite\Admin\Message\Crud;

use KiwiSuite\Admin\Handler\Crud\CreateHandler;
use KiwiSuite\CommandBus\Message\Validation\Result;
use KiwiSuite\Entity\Entity\Definition;
use KiwiSuite\Entity\Entity\EntityInterface;
use KiwiSuite\Entity\Type\Type;

final class CreateMessage extends AbstractCrudMessage
{

    public static function getHandler(): string
    {
        return CreateHandler::class;
    }

    public function fetchEntity(): EntityInterface
    {
        $data = $this->data();
        $data['id'] = $this->uuid();
        if ($this->entityDefinitions()->has("createdAt")) {
            $data['createdAt'] = $this->createdAt();
        }

        $entityClass = $this->repository()->getEntityName();
        return new $entityClass($data);
    }

    protected function doValidate(Result $result): void
    {
        parent::doValidate($result);

        /** @var Definition $definition */
        foreach ($this->entityDefinitions() as $definition) {
            if ($definition->getName() === "id") {
                continue;
            }
            if (empty($this->data()[$definition->getName()])) {
                if ($definition->isNullAble()) {
                    continue;
                }
                if ($definition->hasDefault()) {
                    continue;
                }

                $result->addError("invalid_" . $definition->getName());
                continue;
            }
            try {
                Type::create($this->data()[$definition->getName()], $definition->getType());
            } catch (\Exception $e) {
                $result->addError("invalid_" . $definition->getName());
            }
        }
    }
}
