<?php declare(strict_types=1);

namespace KiwiMigration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use KiwiSuite\CommonTypes\Entity\DateTimeType;

final class Version20180917123947 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $table = $schema->getTable('admin_user');
        $table->addColumn('updatedAt', DateTimeType::class);
        $table->addColumn('deletedAt', DateTimeType::class)->setNotnull(false);
    }

    public function down(Schema $schema) : void
    {
        $table = $schema->getTable('admin_user');
        $table->dropColumn('updatedAt');
        $table->dropColumn('deletedAt');
    }
}
