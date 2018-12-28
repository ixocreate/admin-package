<?php declare(strict_types=1);

namespace IxocreateMigration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Ixocreate\CommonTypes\Entity\SchemaType;

final class Version20181213100000 extends AbstractMigration
{
    /**
     * @param Schema $schema
     * @throws \Doctrine\DBAL\Schema\SchemaException
     */
    public function up(Schema $schema): void
    {
        $table = $schema->getTable('admin_user');
        $table->dropColumn('additionalUserSchema');
        $table->dropColumn('additionalAccountSchema');
        $table->addColumn('userAttributes', SchemaType::serviceName())->setNotnull(false);
        $table->addColumn('accountAttributes', SchemaType::serviceName())->setNotnull(false);
    }

    public function down(Schema $schema): void
    {
        $table = $schema->getTable('admin_user');
        $table->dropColumn('userAttributes');
        $table->dropColumn('accountAttributes');
        $table->addColumn('additionalUserSchema', SchemaType::serviceName())->setNotnull(false);
        $table->addColumn('additionalAccountSchema', SchemaType::serviceName())->setNotnull(false);
    }
}
