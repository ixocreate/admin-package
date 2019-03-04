<?php declare(strict_types=1);

namespace IxocreateMigration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Ixocreate\CommonTypes\Entity\DateTimeType;

final class Version20190228145833 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $table = $schema->getTable('admin_user');
        $table->addColumn('lastPasswordChangeAt', DateTimeType::serviceName())->setNotnull(false);
        $table->addColumn('lastActivityAt', DateTimeType::serviceName())->setNotnull(false);
        $table->dropColumn('hash');
    }

    public function down(Schema $schema) : void
    {
        $table = $schema->getTable('admin_user');
        $table->dropColumn('lastPasswordChangeAt');
        $table->dropColumn('lastActivityAt');
        $table->addColumn('hash', UuidType::serviceName());
    }
}
