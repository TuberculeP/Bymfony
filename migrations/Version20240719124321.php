<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240719124321 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media DROP CONSTRAINT fk_6a2ca10c36aa4bb4');
        $this->addSql('DROP INDEX uniq_6a2ca10c36aa4bb4');
        $this->addSql('ALTER TABLE media ADD drink VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE media DROP drink_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE media ADD drink_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media DROP drink');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT fk_6a2ca10c36aa4bb4 FOREIGN KEY (drink_id) REFERENCES drink (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_6a2ca10c36aa4bb4 ON media (drink_id)');
    }
}
