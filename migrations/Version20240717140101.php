<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240717140101 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE command (id INT NOT NULL, author_id INT NOT NULL, assignee_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(100) NOT NULL, ready_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, paid_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8ECAEAD4F675F31B ON command (author_id)');
        $this->addSql('CREATE INDEX IDX_8ECAEAD459EC7D60 ON command (assignee_id)');
        $this->addSql('COMMENT ON COLUMN command.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN command.ready_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN command.paid_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE drink (id INT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE drink_entry (id INT NOT NULL, drink_id INT NOT NULL, amount INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D5FD4CD536AA4BB4 ON drink_entry (drink_id)');
        $this->addSql('CREATE TABLE media (id INT NOT NULL, drink_id INT DEFAULT NULL, file_path VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6A2CA10C36AA4BB4 ON media (drink_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, fullname VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD4F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD459EC7D60 FOREIGN KEY (assignee_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE drink_entry ADD CONSTRAINT FK_D5FD4CD536AA4BB4 FOREIGN KEY (drink_id) REFERENCES drink (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C36AA4BB4 FOREIGN KEY (drink_id) REFERENCES drink (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE command DROP CONSTRAINT FK_8ECAEAD4F675F31B');
        $this->addSql('ALTER TABLE command DROP CONSTRAINT FK_8ECAEAD459EC7D60');
        $this->addSql('ALTER TABLE drink_entry DROP CONSTRAINT FK_D5FD4CD536AA4BB4');
        $this->addSql('ALTER TABLE media DROP CONSTRAINT FK_6A2CA10C36AA4BB4');
        $this->addSql('DROP TABLE command');
        $this->addSql('DROP TABLE drink');
        $this->addSql('DROP TABLE drink_entry');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE "user"');
    }
}
