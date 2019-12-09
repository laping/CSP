<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191209115000 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE peripherique');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE peripherique (id VARCHAR(30) NOT NULL COLLATE utf8mb4_unicode_ci, machine_id VARCHAR(30) DEFAULT NULL COLLATE utf8mb4_unicode_ci, categorie_id INT DEFAULT NULL, etat_id INT DEFAULT NULL, serial VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, INDEX IDX_CFCF0365BCF5E72D (categorie_id), INDEX IDX_CFCF0365F6B75B26 (machine_id), INDEX IDX_CFCF0365D5E86FF (etat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE peripherique ADD CONSTRAINT FK_CFCF0365BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE peripherique ADD CONSTRAINT FK_CFCF0365D5E86FF FOREIGN KEY (etat_id) REFERENCES etat (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE peripherique ADD CONSTRAINT FK_CFCF0365F6B75B26 FOREIGN KEY (machine_id) REFERENCES machine (id) ON DELETE SET NULL');
    }
}
