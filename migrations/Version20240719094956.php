<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240719094956 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE drink_entry_id_seq CASCADE');
        $this->addSql('CREATE TABLE command_drink (command_id INT NOT NULL, drink_id INT NOT NULL, PRIMARY KEY(command_id, drink_id))');
        $this->addSql('CREATE INDEX IDX_9E1615E233E1689A ON command_drink (command_id)');
        $this->addSql('CREATE INDEX IDX_9E1615E236AA4BB4 ON command_drink (drink_id)');
        $this->addSql('ALTER TABLE command_drink ADD CONSTRAINT FK_9E1615E233E1689A FOREIGN KEY (command_id) REFERENCES command (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE command_drink ADD CONSTRAINT FK_9E1615E236AA4BB4 FOREIGN KEY (drink_id) REFERENCES drink (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE drink_entry DROP CONSTRAINT fk_d5fd4cd536aa4bb4');
        $this->addSql('ALTER TABLE drink_entry DROP CONSTRAINT fk_d5fd4cd533e1689a');
        $this->addSql('DROP TABLE drink_entry');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE drink_entry_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE drink_entry (id INT NOT NULL, drink_id INT NOT NULL, command_id INT NOT NULL, amount INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_d5fd4cd533e1689a ON drink_entry (command_id)');
        $this->addSql('CREATE INDEX idx_d5fd4cd536aa4bb4 ON drink_entry (drink_id)');
        $this->addSql('ALTER TABLE drink_entry ADD CONSTRAINT fk_d5fd4cd536aa4bb4 FOREIGN KEY (drink_id) REFERENCES drink (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE drink_entry ADD CONSTRAINT fk_d5fd4cd533e1689a FOREIGN KEY (command_id) REFERENCES command (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE command_drink DROP CONSTRAINT FK_9E1615E233E1689A');
        $this->addSql('ALTER TABLE command_drink DROP CONSTRAINT FK_9E1615E236AA4BB4');
        $this->addSql('DROP TABLE command_drink');
    }
}
