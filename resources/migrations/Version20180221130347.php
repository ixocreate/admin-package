<?php declare(strict_types = 1);

namespace KiwiMigration;


use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;
use Doctrine\Migrations\AbstractMigration;
use KiwiSuite\Admin\Type\RoleType;
use KiwiSuite\Admin\Type\StatusType;
use KiwiSuite\CommonTypes\Entity\DateTimeType;
use KiwiSuite\CommonTypes\Entity\EmailType;
use KiwiSuite\CommonTypes\Entity\UuidType;

class Version20180221130347 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $table = $schema->createTable('admin_user');
        $table->addColumn('id', UuidType::class);
        $table->addColumn('email', EmailType::class);
        $table->addColumn('password', Type::STRING)->setLength(255);
        $table->addColumn('hash', UuidType::class);
        $table->addColumn('role', RoleType::class)->setLength(255);
        $table->addColumn('avatar', Type::TEXT);
        $table->addColumn("status", StatusType::class, ['default' => 'active']);
        $table->addColumn('createdAt', DateTimeType::class);
        $table->addColumn('lastLoginAt', DateTimeType::class)->setNotnull(false);

        $table->setPrimaryKey(["id"]);
        $table->addUniqueIndex(["email"]);
    }

    public function down(Schema $schema)
    {
        $schema->dropTable("admin_user");
    }
}
