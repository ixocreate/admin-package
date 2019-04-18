<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Package\Entity;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Ixocreate\Admin\Package\Type\RoleType;
use Ixocreate\Admin\Package\Type\StatusType;
use Ixocreate\Type\Package\Entity\DateTimeType;
use Ixocreate\Type\Package\Entity\EmailType;
use Ixocreate\Type\Package\Entity\SchemaType;
use Ixocreate\Type\Package\Entity\UuidType;
use Ixocreate\Admin\Package\RoleInterface;
use Ixocreate\Admin\Package\UserInterface;
use Ixocreate\Entity\DatabaseEntityInterface;
use Ixocreate\Type\Package\TypeInterface;
use Ixocreate\Entity\Package\Definition;
use Ixocreate\Entity\Package\DefinitionCollection;
use Ixocreate\Entity\Package\EntityInterface;
use Ixocreate\Entity\Package\EntityTrait;

final class User implements EntityInterface, DatabaseEntityInterface, UserInterface
{
    use EntityTrait;

    private $id;

    private $email;

    private $password;

    private $status;

    private $role;

    private $avatar;

    private $createdAt;

    private $updatedAt;

    private $deletedAt;

    private $lastLoginAt;

    private $lastActivityAt;

    private $lastPasswordChangeAt;

    private $userAttributes;

    private $accountAttributes;

    private $locale;

    private $numberLocale;

    private $dateLocale;

    private $timeLocale;

    private $timezone;

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

    public function status(): StatusType
    {
        return $this->status;
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

    public function lastActivityAt():? DateTimeType
    {
        return $this->lastActivityAt;
    }

    public function lastPasswordChangeAt():? DateTimeType
    {
        return $this->lastPasswordChangeAt;
    }

    public function userAttributes():? SchemaType
    {
        return $this->userAttributes;
    }

    public function accountAttributes(): ?SchemaType
    {
        return $this->accountAttributes;
    }

    public function locale(): ?string
    {
        return $this->locale;
    }

    public function numberLocale(): ?string
    {
        return $this->numberLocale;
    }

    public function dateLocale(): ?string
    {
        return $this->dateLocale;
    }

    public function timeLocale(): ?string
    {
        return $this->timeLocale;
    }

    public function timezone(): ?string
    {
        return $this->timezone;
    }

    /**
     * @return RoleInterface
     */
    public function getRole(): RoleInterface
    {
        return $this->role()->getRole();
    }

    protected static function createDefinitions() : DefinitionCollection
    {
        return new DefinitionCollection([
            new Definition("id", UuidType::class, false, true),
            new Definition("email", EmailType::class, false, true),
            new Definition("password", TypeInterface::TYPE_STRING, false, false),
            new Definition("status", StatusType::class, false, true),
            new Definition("role", RoleType::class, false, true),
            new Definition("avatar", TypeInterface::TYPE_STRING, false, true),
            new Definition("createdAt", DateTimeType::class, false, true),
            new Definition("updatedAt", DateTimeType::class, false, true),
            new Definition("deletedAt", DateTimeType::class, true, false),
            new Definition("lastLoginAt", DateTimeType::class, true, true),
            new Definition("lastActivityAt", DateTimeType::class, true, true),
            new Definition("lastPasswordChangeAt", DateTimeType::class, true, true),
            new Definition("userAttributes", SchemaType::class, true, true),
            new Definition("accountAttributes", SchemaType::class, true, true),
            new Definition("locale", TypeInterface::TYPE_STRING, true, true),
            new Definition("numberLocale", TypeInterface::TYPE_STRING, true, true),
            new Definition("dateLocale", TypeInterface::TYPE_STRING, true, true),
            new Definition("timeLocale", TypeInterface::TYPE_STRING, true, true),
            new Definition("timezone", TypeInterface::TYPE_STRING, true, true),
        ]);
    }

    public static function loadMetadata(ClassMetadataBuilder $builder)
    {
        $builder->setTable('admin_user');

        $builder->createField('id', UuidType::serviceName())
            ->makePrimaryKey()
            ->build();

        $builder->addField('email', EmailType::serviceName());
        $builder->addField('password', Type::STRING);
        $builder->addField('status', StatusType::serviceName());
        $builder->addField('role', RoleType::serviceName());
        $builder->addField('avatar', Type::TEXT);
        $builder->addField('createdAt', DateTimeType::serviceName());
        $builder->addField('updatedAt', DateTimeType::serviceName());
        $builder->addField('deletedAt', DateTimeType::serviceName());
        $builder->addField('lastLoginAt', DateTimeType::serviceName());
        $builder->addField('lastActivityAt', DateTimeType::serviceName());
        $builder->addField('lastPasswordChangeAt', DateTimeType::serviceName());
        $builder->addField('userAttributes', SchemaType::serviceName());
        $builder->addField('accountAttributes', SchemaType::serviceName());
        $builder->addField('locale', Type::STRING);
        $builder->addField('numberLocale', Type::STRING);
        $builder->addField('dateLocale', Type::STRING);
        $builder->addField('timeLocale', Type::STRING);
        $builder->addField('timezone', Type::STRING);
    }
}
