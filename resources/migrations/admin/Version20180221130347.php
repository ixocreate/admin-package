<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;
use Doctrine\Migrations\AbstractMigration;
use Ixocreate\Admin\Schema\Type\RoleType;
use Ixocreate\Admin\Schema\Type\StatusType;
use Ixocreate\Schema\Type\DateTimeType;
use Ixocreate\Schema\Type\EmailType;
use Ixocreate\Schema\Type\SchemaType;
use Ixocreate\Schema\Type\TypeInterface;
use Ixocreate\Schema\Type\UuidType;

class Version20180221130347 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $table = $schema->createTable('admin_user');
        $table->addColumn('id', UuidType::serviceName());
        $table->addColumn('email', EmailType::serviceName());
        $table->addColumn('password', Type::STRING)->setLength(255);
        $table->addColumn('role', RoleType::serviceName())->setLength(255);
        $table->addColumn('avatar', Type::TEXT);
        $table->addColumn('status', StatusType::serviceName(), ['default' => 'active']);
        $table->addColumn('locale', TypeInterface::TYPE_STRING)->setNotnull(false);
        $table->addColumn('numberLocale', TypeInterface::TYPE_STRING)->setNotnull(false);
        $table->addColumn('dateLocale', TypeInterface::TYPE_STRING)->setNotnull(false);
        $table->addColumn('timeLocale', TypeInterface::TYPE_STRING)->setNotnull(false);
        $table->addColumn('timezone', TypeInterface::TYPE_STRING)->setNotnull(false);
        $table->addColumn('userAttributes', SchemaType::serviceName())->setNotnull(false);
        $table->addColumn('accountAttributes', SchemaType::serviceName())->setNotnull(false);
        $table->addColumn('lastLoginAt', DateTimeType::serviceName())->setNotnull(false);
        $table->addColumn('lastActivityAt', DateTimeType::serviceName())->setNotnull(false);
        $table->addColumn('lastPasswordChangeAt', DateTimeType::serviceName())->setNotnull(false);
        $table->addColumn('createdAt', DateTimeType::serviceName());
        $table->addColumn('updatedAt', DateTimeType::serviceName());
        $table->addColumn('deletedAt', DateTimeType::serviceName())->setNotnull(false);

        $table->setPrimaryKey(["id"]);
        $table->addUniqueIndex(["email"]);
    }

    public function down(Schema $schema)
    {
        $schema->dropTable("admin_user");
    }
}
