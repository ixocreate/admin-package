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

    private $username;

    private function createDefinitions() : DefinitionCollection
    {
        return new DefinitionCollection([
            new Definition("id", TypeInterface::TYPE_INT, false, true),
            new Definition("email", TypeInterface::TYPE_STRING, false, true),
            new Definition("password", TypeInterface::TYPE_STRING, false, false),
            new Definition("username", TypeInterface::TYPE_STRING, false, true),
        ]);
    }
}
