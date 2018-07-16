<?php
/**
 * kiwi-suite/admin (https://github.com/kiwi-suite/admin)
 *
 * @package   kiwi-suite/admin
 * @see       https://github.com/kiwi-suite/admin
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license   MIT License
 */

declare(strict_types=1);

namespace KiwiSuite\Admin\Resource;

use KiwiSuite\Admin\Action\Api\User\CreateAction;
use KiwiSuite\Admin\Entity\User;
use KiwiSuite\Admin\Repository\UserRepository;
use KiwiSuite\Contract\Resource\AdminAwareInterface;
use KiwiSuite\Contract\Schema\BuilderInterface;
use KiwiSuite\Contract\Schema\Listing\ListSchemaInterface;
use KiwiSuite\Contract\Schema\SchemaInterface;
use KiwiSuite\Schema\Listing\ListSchema;

final class UserResource implements AdminAwareInterface
{
    use DefaultAdminTrait;

    public function label(): string
    {
        return "User";
    }

    public static function serviceName(): string
    {
        return 'admin-user';
    }

    public function repository(): string
    {
        return UserRepository::class;
    }

    public function createAction(): ?string
    {
        return CreateAction::class;
    }

    /**
     * @param BuilderInterface $builder
     * @return SchemaInterface
     */
    public function createSchema(BuilderInterface $builder): SchemaInterface
    {
        /** @var SchemaInterface $schema */
        $schema = $builder->fromEntity(User::class);

        $schema = $schema->remove('hash');
        $schema = $schema->remove('avatar');
        $schema = $schema->remove('lastLoginAt');


        return $schema;
    }

    /**
     * @param BuilderInterface $builder
     * @return SchemaInterface
     */
    public function updateSchema(BuilderInterface $builder): SchemaInterface
    {
        /** @var SchemaInterface $schema */
        $schema = $builder->fromEntity(User::class);

        $schema = $schema->remove('password');
        $schema = $schema->remove('hash');
        $schema = $schema->remove('avatar');
        $schema = $schema->remove('lastLoginAt');


        return $schema;
    }

    /**
     * @return ListSchemaInterface
     */
    public function listSchema(): ListSchemaInterface
    {
        return new ListSchema();
    }
}
