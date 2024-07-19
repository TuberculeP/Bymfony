<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240719131750 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE drink DROP media');
        $this->addSql('ALTER TABLE media ADD drink_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media DROP drink');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C36AA4BB4 FOREIGN KEY (drink_id) REFERENCES drink (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6A2CA10C36AA4BB4 ON media (drink_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE media DROP CONSTRAINT FK_6A2CA10C36AA4BB4');
        $this->addSql('DROP INDEX UNIQ_6A2CA10C36AA4BB4');
        $this->addSql('ALTER TABLE media ADD drink VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE media DROP drink_id');
        $this->addSql('ALTER TABLE drink ADD media VARCHAR(255) DEFAULT NULL');
    }
}
