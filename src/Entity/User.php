<?php
namespace KiwiSuite\Admin\Entity;

use KiwiSuite\Entity\Entity\Definition;
use KiwiSuite\Entity\Entity\DefinitionCollection;
use KiwiSuite\Entity\Entity\EntityInterface;
use KiwiSuite\Entity\Entity\EntityTrait;
use KiwiSuite\Entity\Type\TypeInterface;

final class User implements EntityInterface
{
    use EntityTrait;

    private $id;

    private $email;

    private $password;

    private function createDefinitions() : DefinitionCollection
    {
        return new DefinitionCollection([
            new Definition("id", TypeInterface::TYPE_INT, false, true),
            new Definition("email", TypeInterface::TYPE_STRING, false, true),
            new Definition("password", TypeInterface::TYPE_STRING, false, false),
        ]);
    }
}
