<?php
namespace KiwiSuite\Admin\Message\Crud;

use KiwiSuite\Admin\Handler\Crud\DeleteHandler;
use KiwiSuite\CommandBus\Message\Validation\Result;
use KiwiSuite\Entity\Entity\EntityInterface;

final class DeleteMessage extends AbstractCrudMessage
{

    public static function getHandler(): string
    {
        return DeleteHandler::class;
    }

    public function fetchEntity(): EntityInterface
    {
        return $this->entity();
    }

    protected function doValidate(Result $result): void
    {
        parent::doValidate($result);

        if (empty($this->entity())) {
            $result->addError("invalid_id");
        }
    }
}
