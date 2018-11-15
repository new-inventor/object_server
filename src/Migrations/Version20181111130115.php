<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181111130115 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE FUNCTION match_server_hash(webServerServerHash VARCHAR(255))
  RETURNS BOOLEAN
  BEGIN
    RETURN if(concat(
                  \'|e:\',
                  (SELECT GROUP_CONCAT(element.id ORDER BY element.id ASC SEPARATOR \',\') from element),
                  \'|r:\',
                  (SELECT GROUP_CONCAT(room.id ORDER BY room.id ASC SEPARATOR \',\') from room),
                  \'|t:\',
                  (SELECT GROUP_CONCAT(event_trigger.id ORDER BY event_trigger.id ASC SEPARATOR \',\') from event_trigger)
              ) = webServerServerHash, 1, 0);
  END;');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('drop function if exists match_server_hash(webServerServerHash VARCHAR(255))');
    }
}
