<?php declare(strict_types=1);

namespace IxocreateMigration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Ixocreate\Contract\Type\TypeInterface;

final class Version20190307133333 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $table = $schema->getTable('admin_user');
        $table->addColumn('locale', TypeInterface::TYPE_STRING)->setNotnull(false);
        $table->addColumn('numberLocale', TypeInterface::TYPE_STRING)->setNotnull(false);
        $table->addColumn('dateLocale', TypeInterface::TYPE_STRING)->setNotnull(false);
        $table->addColumn('timeLocale', TypeInterface::TYPE_STRING)->setNotnull(false);
        $table->addColumn('timezone', TypeInterface::TYPE_STRING)->setNotnull(false);
    }

    public function down(Schema $schema): void
    {
        $table = $schema->getTable('admin_user');
        $table->dropColumn('locale');
        $table->dropColumn('numberLocale');
        $table->dropColumn('dateLocale');
        $table->dropColumn('timeLocale');
        $table->dropColumn('timezone');
    }
}
