<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260625153703 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__council_sessions AS SELECT id, town_hall_code, session_date, session_type, status, invitations_sent, deliberation_sequence FROM council_sessions');
        $this->addSql('DROP TABLE council_sessions');
        $this->addSql('CREATE TABLE council_sessions (id VARCHAR(36) NOT NULL, town_hall_code VARCHAR(20) NOT NULL, session_date DATETIME NOT NULL, session_type VARCHAR(20) NOT NULL, status VARCHAR(20) NOT NULL, invitations_sent BOOLEAN NOT NULL, deliberation_sequence INTEGER NOT NULL, PRIMARY KEY (id))');
        $this->addSql('INSERT INTO council_sessions (id, town_hall_code, session_date, session_type, status, invitations_sent, deliberation_sequence) SELECT id, town_hall_code, session_date, session_type, status, invitations_sent, deliberation_sequence FROM __temp__council_sessions');
        $this->addSql('DROP TABLE __temp__council_sessions');
        $this->addSql('ALTER TABLE councilors ADD COLUMN town_hall_code VARCHAR(20) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE council_sessions ADD COLUMN order_of_business CLOB NOT NULL');
        $this->addSql('CREATE TEMPORARY TABLE __temp__councilors AS SELECT id, first_name, last_name, email, role, active FROM councilors');
        $this->addSql('DROP TABLE councilors');
        $this->addSql('CREATE TABLE councilors (id VARCHAR(36) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, role VARCHAR(50) NOT NULL, active BOOLEAN NOT NULL, PRIMARY KEY (id))');
        $this->addSql('INSERT INTO councilors (id, first_name, last_name, email, role, active) SELECT id, first_name, last_name, email, role, active FROM __temp__councilors');
        $this->addSql('DROP TABLE __temp__councilors');
    }
}
