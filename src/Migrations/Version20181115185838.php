<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181115185838 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $schema->getTable('actuator')->addColumn('log_type', 'string', ['Notnull' => true]);
        $schema->getTable('sensor')->addColumn('log_type', 'string', ['Notnull' => true]);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $schema->getTable('actuator')->dropColumn('log_type');
        $schema->getTable('sensor')->dropColumn('log_type');

    }
}
