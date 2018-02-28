<?php
namespace KiwiSuite\Admin\Message\Crud;

use KiwiSuite\Admin\Handler\Crud\UpdateHandler;
use KiwiSuite\CommandBus\Message\Validation\Result;
use KiwiSuite\Entity\Entity\Definition;
use KiwiSuite\Entity\Entity\EntityInterface;
use KiwiSuite\Entity\Type\Type;

final class UpdateMessage extends AbstractCrudMessage
{

    public static function getHandler(): string
    {
        return UpdateHandler::class;
    }

    public function fetchEntity(): EntityInterface
    {
        $entity = $this->entity();

        foreach ($this->data() as $key => $value) {
            $entity = $entity->with($key, $value);
        }

        return $entity;
    }

    protected function doValidate(Result $result): void
    {
        parent::doValidate($result);

        if (empty($this->entity())) {
            $result->addError("invalid_id");
            return;
        }

        $definitionCollection = $this->entityDefinitions();

        foreach ($this->data() as $key => $value) {
            if (!$definitionCollection->has($key)) {
                $result->addError("invalid_" . $key);

                continue;
            }

            /** @var Definition $definition */
            $definition = $definitionCollection->get($key);

            try {
                Type::create($this->data()[$definition->getName()], $definition->getType());
            } catch (\Exception $e) {
                $result->addError("invalid_" . $definition->getName());
            }
        }
    }
}
