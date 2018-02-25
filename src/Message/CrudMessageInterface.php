<?php
namespace KiwiSuite\Admin\Message;

use KiwiSuite\Entity\Entity\EntityInterface;

interface CrudMessageInterface
{
    public function fetchEntity(): EntityInterface;
}
