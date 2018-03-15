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
use KiwiSuite\Entity\Entity\EntityInterface;

final class DeleteMessage extends AbstractCrudMessage
{
    /**
     * @return array
     */
    public function handlers(): array
    {
        return $this->resource()->deleteHandler();
    }

    public function fetchEntity(): EntityInterface
    {
        return $this->repository()->findOneBy(['id' => $this->metadata()['id']]);
    }

    protected function doValidate(Result $result): void
    {
        parent::doValidate($result);

        if (empty($this->entity())) {
            $result->addError("invalid_id");
        }
    }
}
