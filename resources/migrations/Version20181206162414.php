<?php declare(strict_types=1);

namespace KiwiMigration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use KiwiSuite\CommonTypes\Entity\SchemaType;

final class Version20181206162414 extends AbstractMigration
{
    /**
     * @param Schema $schema
     * @throws \Doctrine\DBAL\Schema\SchemaException
     */
    public function up(Schema $schema): void
    {
        $table = $schema->getTable('admin_user');
        $table->dropColumn('additionalFields');
        $table->addColumn('additionalUserSchema', SchemaType::serviceName())->setNotnull(false);
        $table->addColumn('additionalAccountSchema', SchemaType::serviceName())->setNotnull(false);
    }

    public function down(Schema $schema): void
    {
        $table = $schema->getTable('admin_user');
        $table->dropColumn('additionalUserSchema');
        $table->dropColumn('additionalAccountSchema');
        $table->addColumn('additionalFields', SchemaType::serviceName())->setNotnull(false);
    }
}
