<?php
declare(strict_types=1);

namespace Ixocreate\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Ixocreate\Type\Package\Entity\SchemaType;

final class Version20181130114739 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $table = $schema->getTable('admin_user');
        $table->addColumn('additionalFields', SchemaType::serviceName())->setNotnull(false);

    }

    public function down(Schema $schema): void
    {
        $table = $schema->getTable('admin_user');
        $table->dropColumn('additionalFields');
    }
}
