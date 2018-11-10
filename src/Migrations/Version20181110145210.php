<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181110145210 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE PROCEDURE match_object_hash(OUT isMatch BOOLEAN, IN webServerObjectHash VARCHAR(255))
  BEGIN
    select if(concat(
                \'|a:\',
                (SELECT GROUP_CONCAT(actuator.id ORDER BY actuator.id ASC SEPARATOR \',\') from actuator),
                \'|c:\',
                (SELECT GROUP_CONCAT(controller.id ORDER BY controller.id ASC SEPARATOR \',\') from controller),
                \'|s:\',
                (SELECT GROUP_CONCAT(sensor.id ORDER BY sensor.id ASC SEPARATOR \',\') from sensor)
                  ) = webServerObjectHash, 1, 0)  Into isMatch;
  END');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('Drop Procedure make_object_hash(OUT isMatch BOOLEAN, IN webServerObjectHash VARCHAR(255))');
    }
}
