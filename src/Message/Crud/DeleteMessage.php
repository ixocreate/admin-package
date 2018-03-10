<?php
namespace KiwiSuite\Admin\Message\Crud;

use KiwiSuite\Admin\Handler\Crud\DeleteHandler;
use KiwiSuite\Admin\Message\CrudMessageInterface;
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
