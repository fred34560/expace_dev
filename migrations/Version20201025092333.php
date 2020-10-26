<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201025092333 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE devis ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52BA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_8B27C52BA76ED395 ON devis (user_id)');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E941DEFADA');
        $this->addSql('DROP INDEX IDX_1483A5E941DEFADA ON users');
        $this->addSql('ALTER TABLE users DROP devis_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52BA76ED395');
        $this->addSql('DROP INDEX IDX_8B27C52BA76ED395 ON devis');
        $this->addSql('ALTER TABLE devis DROP user_id');
        $this->addSql('ALTER TABLE users ADD devis_id INT NOT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E941DEFADA FOREIGN KEY (devis_id) REFERENCES devis (id)');
        $this->addSql('CREATE INDEX IDX_1483A5E941DEFADA ON users (devis_id)');
    }
}
