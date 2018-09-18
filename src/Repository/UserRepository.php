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

namespace KiwiSuite\Admin\Repository;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use KiwiSuite\Admin\Entity\User;
use KiwiSuite\Admin\Type\RoleType;
use KiwiSuite\Admin\Type\StatusType;
use KiwiSuite\CommonTypes\Entity\DateTimeType;
use KiwiSuite\CommonTypes\Entity\EmailType;
use KiwiSuite\CommonTypes\Entity\UuidType;
use KiwiSuite\Database\Repository\AbstractRepository;

final class UserRepository extends AbstractRepository
{

    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return User::class;
    }

    public function loadMetadata(ClassMetadataBuilder $metadata): void
    {
        $metadata->setTable('admin_user');

        $metadata->createField('id', UuidType::class)
            ->makePrimaryKey()
            ->build();

        $metadata->addField('email', EmailType::class);
        $metadata->addField('password', Type::STRING);
        $metadata->addField('hash', UuidType::class);
        $metadata->addField('role', RoleType::class);
        $metadata->addField('avatar', Type::TEXT);
        $metadata->addField('createdAt', DateTimeType::class);
        $metadata->addField('lastLoginAt', DateTimeType::class);
        $metadata->addField('updatedAt', DateTimeType::class);
        $metadata->addField('deletedAt', DateTimeType::class);
        $metadata->addField('status', StatusType::class);
    }
}
