<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201029072005 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE messages ADD client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E9619EB6921 FOREIGN KEY (client_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_DB021E9619EB6921 ON messages (client_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E9619EB6921');
        $this->addSql('DROP INDEX IDX_DB021E9619EB6921 ON messages');
        $this->addSql('ALTER TABLE messages DROP client_id');
    }
}
