<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200704075104 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cahier_charge (id INT AUTO_INCREMENT NOT NULL, cahier_charge_id INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, fichier VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1EA467DDC94DDD7 (cahier_charge_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE proposition_commercial (id INT AUTO_INCREMENT NOT NULL, projet_id INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, fichier VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_5E27A760C18272 (projet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cahier_charge ADD CONSTRAINT FK_1EA467DDC94DDD7 FOREIGN KEY (cahier_charge_id) REFERENCES projets (id)');
        $this->addSql('ALTER TABLE proposition_commercial ADD CONSTRAINT FK_5E27A760C18272 FOREIGN KEY (projet_id) REFERENCES projets (id)');
        $this->addSql('ALTER TABLE projets DROP proposition_commercial, DROP cahier_charge, DROP devis');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE cahier_charge');
        $this->addSql('DROP TABLE proposition_commercial');
        $this->addSql('ALTER TABLE projets ADD proposition_commercial VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD cahier_charge VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD devis VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
