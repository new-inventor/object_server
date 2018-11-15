<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181111151052 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $schema->getTable('actuator')->addColumn('description', 'string', ['default' => '']);
        $schema->getTable('sensor')->addColumn('description', 'string', ['default' => '']);
        $schema->getTable('controller')->addColumn('serial', 'string', ['unique' => true, 'length' => '50']);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $schema->getTable('actuator')->dropColumn('description');
        $schema->getTable('sensor')->dropColumn('description');
        $schema->getTable('controller')->dropColumn('serial');

    }
}
