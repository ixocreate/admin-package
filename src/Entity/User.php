<?php
/**
 * kiwi-suite/admin (https://github.com/kiwi-suite/admin)
 *
 * @package kiwi-suite/admin
 * @link https://github.com/kiwi-suite/admin
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace KiwiSuite\Admin\Entity;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use KiwiSuite\Admin\Type\RoleType;
use KiwiSuite\Admin\Type\StatusType;
use KiwiSuite\CommonTypes\Entity\DateTimeType;
use KiwiSuite\CommonTypes\Entity\EmailType;
use KiwiSuite\CommonTypes\Entity\SchemaType;
use KiwiSuite\CommonTypes\Entity\UuidType;
use KiwiSuite\Contract\Entity\DatabaseEntityInterface;
use KiwiSuite\Contract\Type\TypeInterface;
use KiwiSuite\Entity\Entity\Definition;
use KiwiSuite\Entity\Entity\DefinitionCollection;
use KiwiSuite\Entity\Entity\EntityInterface;
use KiwiSuite\Entity\Entity\EntityTrait;

final class User implements EntityInterface, DatabaseEntityInterface
{
    use EntityTrait;

    private $id;

    private $email;

    private $password;

    private $hash;

    private $role;

    private $avatar;

    private $createdAt;

    private $updatedAt;

    private $deletedAt;

    private $lastLoginAt;

    private $status;

    private $additionalFields;

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

    public function updatedAt(): DateTimeType
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?DateTimeType
    {
        return $this->deletedAt;
    }

    public function lastLoginAt():? DateTimeType
    {
        return $this->lastLoginAt;
    }

    public function status(): StatusType
    {
        return $this->status;
    }

    public function additionalFields():? SchemaType
    {
        return $this->additionalFields;
    }

    protected static function createDefinitions() : DefinitionCollection
    {
        return new DefinitionCollection([
            new Definition("id", UuidType::class, false, true),
            new Definition("email", EmailType::class, false, true),
            new Definition("password", TypeInterface::TYPE_STRING, false, false),
            new Definition("hash", UuidType::class, false, false),
            new Definition("role", RoleType::class, false, true),
            new Definition("avatar", TypeInterface::TYPE_STRING, false, true),
            new Definition("createdAt", DateTimeType::class, false, true),
            new Definition("updatedAt", DateTimeType::class, false, true),
            new Definition("deletedAt", DateTimeType::class, true, false),
            new Definition("lastLoginAt", DateTimeType::class, true, true),
            new Definition("status", StatusType::class, false, true),
            new Definition('additionalFields', SchemaType::class, true, true)
        ]);
    }

    public static function loadMetadata(ClassMetadataBuilder $builder)
    {
        $builder->setTable('admin_user');

        $builder->createField('id', UuidType::class)
            ->makePrimaryKey()
            ->build();

        $builder->addField('email', EmailType::serviceName());
        $builder->addField('password', Type::STRING);
        $builder->addField('hash', UuidType::serviceName());
        $builder->addField('role', RoleType::serviceName());
        $builder->addField('avatar', Type::TEXT);
        $builder->addField('createdAt', DateTimeType::serviceName());
        $builder->addField('lastLoginAt', DateTimeType::serviceName());
        $builder->addField('updatedAt', DateTimeType::serviceName());
        $builder->addField('deletedAt', DateTimeType::serviceName());
        $builder->addField('status', StatusType::serviceName());
        $builder->addField('additionalFields', SchemaType::serviceName());
    }
}
