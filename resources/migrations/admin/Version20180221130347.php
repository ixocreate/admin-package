<?php
declare(strict_types=1);

namespace Ixocreate\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;
use Doctrine\Migrations\AbstractMigration;
use Ixocreate\Admin\Package\Type\RoleType;
use Ixocreate\Admin\Package\Type\StatusType;
use Ixocreate\Type\Package\Entity\DateTimeType;
use Ixocreate\Type\Package\Entity\EmailType;
use Ixocreate\Type\Package\Entity\UuidType;

class Version20180221130347 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $table = $schema->createTable('admin_user');
        $table->addColumn('id', UuidType::serviceName());
        $table->addColumn('email', EmailType::serviceName());
        $table->addColumn('password', Type::STRING)->setLength(255);
        $table->addColumn('hash', UuidType::serviceName());
        $table->addColumn('role', RoleType::serviceName())->setLength(255);
        $table->addColumn('avatar', Type::TEXT);
        $table->addColumn("status", StatusType::serviceName(), ['default' => 'active']);
        $table->addColumn('createdAt', DateTimeType::serviceName());
        $table->addColumn('lastLoginAt', DateTimeType::serviceName())->setNotnull(false);

        $table->setPrimaryKey(["id"]);
        $table->addUniqueIndex(["email"]);
    }

    public function down(Schema $schema)
    {
        $schema->dropTable("admin_user");
    }
}
