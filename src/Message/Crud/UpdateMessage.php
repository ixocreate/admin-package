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

namespace KiwiSuite\Admin\Message\Crud;

use KiwiSuite\CommandBus\Message\Validation\Result;
use KiwiSuite\Entity\Entity\Definition;
use KiwiSuite\Entity\Entity\EntityInterface;
use KiwiSuite\Entity\Type\Type;

final class UpdateMessage extends AbstractCrudMessage
{

    /**
     * @return array
     */
    public function handlers(): array
    {
        return $this->resource()->updateHandler();
    }

    public function fetchEntity(): EntityInterface
    {
        $entity = $this->repository()->findOneBy(['id' => $this->metadata()['id']]);

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

            if (empty($value) && $definition->isNullAble()) {
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
