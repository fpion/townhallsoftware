<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260625122404 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE council_sessions (id VARCHAR(36) NOT NULL, town_hall_code VARCHAR(20) NOT NULL, session_date DATETIME NOT NULL, order_of_business CLOB NOT NULL, session_type VARCHAR(20) NOT NULL, status VARCHAR(20) NOT NULL, invitations_sent BOOLEAN NOT NULL, deliberation_sequence INTEGER NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE councilors (id VARCHAR(36) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, role VARCHAR(50) NOT NULL, active BOOLEAN NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE session_attendances (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, councilor_id VARCHAR(36) NOT NULL, status VARCHAR(30) NOT NULL, proxy_holder_id VARCHAR(36) DEFAULT NULL, session_id VARCHAR(36) NOT NULL, CONSTRAINT FK_603291A3613FECDF FOREIGN KEY (session_id) REFERENCES council_sessions (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_603291A3613FECDF ON session_attendances (session_id)');
        $this->addSql('CREATE TABLE session_deliberations (id VARCHAR(36) NOT NULL, number VARCHAR(20) NOT NULL, title VARCHAR(500) NOT NULL, description CLOB NOT NULL, status VARCHAR(20) NOT NULL, vote_pour INTEGER DEFAULT NULL, vote_contre INTEGER DEFAULT NULL, vote_abstention INTEGER DEFAULT NULL, session_id VARCHAR(36) NOT NULL, PRIMARY KEY (id), CONSTRAINT FK_F5C6E628613FECDF FOREIGN KEY (session_id) REFERENCES council_sessions (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_F5C6E628613FECDF ON session_deliberations (session_id)');
        $this->addSql('CREATE TABLE town_halls (code VARCHAR(20) NOT NULL, name VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, postal_code VARCHAR(10) NOT NULL, population INTEGER NOT NULL, PRIMARY KEY (code))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE council_sessions');
        $this->addSql('DROP TABLE councilors');
        $this->addSql('DROP TABLE session_attendances');
        $this->addSql('DROP TABLE session_deliberations');
        $this->addSql('DROP TABLE town_halls');
    }
}
