<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240717140421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE drink_entry ADD command_id INT NOT NULL');
        $this->addSql('ALTER TABLE drink_entry ADD CONSTRAINT FK_D5FD4CD533E1689A FOREIGN KEY (command_id) REFERENCES command (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D5FD4CD533E1689A ON drink_entry (command_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE drink_entry DROP CONSTRAINT FK_D5FD4CD533E1689A');
        $this->addSql('DROP INDEX IDX_D5FD4CD533E1689A');
        $this->addSql('ALTER TABLE drink_entry DROP command_id');
    }
}
