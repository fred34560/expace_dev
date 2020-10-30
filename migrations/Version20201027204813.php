<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201027204813 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE stats_develop (id INT AUTO_INCREMENT NOT NULL, projet VARCHAR(255) NOT NULL, recompense TINYINT(1) NOT NULL, duree VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52BA76ED395');
        $this->addSql('DROP INDEX IDX_8B27C52BA76ED395 ON devis');
        $this->addSql('ALTER TABLE devis DROP client, CHANGE user_id client_id INT NOT NULL');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B19EB6921 FOREIGN KEY (client_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_8B27C52B19EB6921 ON devis (client_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE stats_develop');
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52B19EB6921');
        $this->addSql('DROP INDEX IDX_8B27C52B19EB6921 ON devis');
        $this->addSql('ALTER TABLE devis ADD client LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', CHANGE client_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52BA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_8B27C52BA76ED395 ON devis (user_id)');
    }
}
