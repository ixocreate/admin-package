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

use KiwiSuite\Admin\Type\RoleType;
use KiwiSuite\Admin\Type\StatusType;
use KiwiSuite\CommonTypes\Entity\DateTimeType;
use KiwiSuite\CommonTypes\Entity\EmailType;
use KiwiSuite\CommonTypes\Entity\UuidType;
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

    private $hash;

    private $role;

    private $avatar;

    private $createdAt;

    private $lastLoginAt;

    private $status;

    public function id(): UuidType
    {
        return $this->id;
    }

    public function email(): EmailType
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function hash(): UuidType
    {
        return $this->hash;
    }

    public function role(): RoleType
    {
        return $this->role;
    }

    public function avatar(): string
    {
        return $this->avatar;
    }

    public function createdAt(): DateTimeType
    {
        return $this->createdAt;
    }

    public function lastLoginAt():? DateTimeType
    {
        return $this->lastLoginAt;
    }

    public function status(): StatusType
    {
        return $this->status;
    }

    private function createDefinitions() : DefinitionCollection
    {
        return new DefinitionCollection([
            new Definition("id", UuidType::class, false, true),
            new Definition("email", EmailType::class, false, true),
            new Definition("password", TypeInterface::TYPE_STRING, false, false),
            new Definition("hash", UuidType::class, false, false),
            new Definition("role", RoleType::class, false, true),
            new Definition("avatar", TypeInterface::TYPE_STRING, false, true),
            new Definition("createdAt", DateTimeType::class, false, true),
            new Definition("lastLoginAt", DateTimeType::class, true, true),
            new Definition("status", StatusType::class, false, true),
        ]);
    }
}
