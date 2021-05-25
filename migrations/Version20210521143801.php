<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210521143801 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE video ADD artist_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE video ADD genre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2CB7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2C4296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_7CC7DA2CB7970CF8 ON video (artist_id)');
        $this->addSql('CREATE INDEX IDX_7CC7DA2C4296D31F ON video (genre_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE video DROP CONSTRAINT FK_7CC7DA2CB7970CF8');
        $this->addSql('ALTER TABLE video DROP CONSTRAINT FK_7CC7DA2C4296D31F');
        $this->addSql('DROP INDEX IDX_7CC7DA2CB7970CF8');
        $this->addSql('DROP INDEX IDX_7CC7DA2C4296D31F');
        $this->addSql('ALTER TABLE video DROP artist_id');
        $this->addSql('ALTER TABLE video DROP genre_id');
    }
}
