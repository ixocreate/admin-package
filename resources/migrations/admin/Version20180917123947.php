<?php
declare(strict_types=1);

namespace Ixocreate\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Ixocreate\Package\Type\Entity\DateTimeType;

final class Version20180917123947 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $table = $schema->getTable('admin_user');
        $table->addColumn('updatedAt', DateTimeType::serviceName());
        $table->addColumn('deletedAt', DateTimeType::serviceName())->setNotnull(false);
    }

    public function down(Schema $schema): void
    {
        $table = $schema->getTable('admin_user');
        $table->dropColumn('updatedAt');
        $table->dropColumn('deletedAt');
    }
}
