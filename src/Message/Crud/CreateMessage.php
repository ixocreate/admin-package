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

final class CreateMessage extends AbstractCrudMessage
{
    /**
     * @return array
     */
    public function handlers(): array
    {
        return $this->resource()->createHandler();
    }

    public function fetchEntity(): EntityInterface
    {
        $data = $this->data();
        $data['id'] = $this->uuid();
        if ($this->entityDefinitions()->has("createdAt")) {
            $data['createdAt'] = $this->createdAt();
        }
        if ($this->entityDefinitions()->has("updatedAt")) {
            $data['updatedAt'] = $this->createdAt();
        }

        $entityClass = $this->repository()->getEntityName();
        return new $entityClass($data);
    }

    protected function doValidate(Result $result): void
    {
        parent::doValidate($result);

        /** @var Definition $definition */
        foreach ($this->entityDefinitions() as $definition) {
            if (\in_array($definition->getName(), ['id', 'createdAt', 'updatedAt'])) {
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
