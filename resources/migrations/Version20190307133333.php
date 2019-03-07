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
        $table->addColumn('number_locale', TypeInterface::TYPE_STRING)->setNotnull(false);
        $table->addColumn('date_locale', TypeInterface::TYPE_STRING)->setNotnull(false);
        $table->addColumn('time_locale', TypeInterface::TYPE_STRING)->setNotnull(false);
        $table->addColumn('timezone', TypeInterface::TYPE_STRING)->setNotnull(false);
    }

    public function down(Schema $schema): void
    {
        $table = $schema->getTable('admin_user');
        $table->dropColumn('locale');
        $table->dropColumn('number_locale');
        $table->dropColumn('date_locale');
        $table->dropColumn('time_locale');
        $table->dropColumn('timezone');
    }
}
