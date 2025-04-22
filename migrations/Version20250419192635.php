<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250419192635 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE comparator (id SERIAL NOT NULL, criteria_id INT NOT NULL, key VARCHAR(100) NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_31B0A4F3990BEA15 ON comparator (criteria_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE criteria (id SERIAL NOT NULL, name VARCHAR(100) NOT NULL, type VARCHAR(50) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE filter (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE filter_setting (id SERIAL NOT NULL, filter_id INT NOT NULL, criteria_id INT NOT NULL, comparator_id INT NOT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_28E4CFFDD395B25E ON filter_setting (filter_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_28E4CFFD990BEA15 ON filter_setting (criteria_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_28E4CFFDD0C9C2E9 ON filter_setting (comparator_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comparator ADD CONSTRAINT FK_31B0A4F3990BEA15 FOREIGN KEY (criteria_id) REFERENCES criteria (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE filter_setting ADD CONSTRAINT FK_28E4CFFDD395B25E FOREIGN KEY (filter_id) REFERENCES filter (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE filter_setting ADD CONSTRAINT FK_28E4CFFD990BEA15 FOREIGN KEY (criteria_id) REFERENCES criteria (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE filter_setting ADD CONSTRAINT FK_28E4CFFDD0C9C2E9 FOREIGN KEY (comparator_id) REFERENCES comparator (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comparator DROP CONSTRAINT FK_31B0A4F3990BEA15
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE filter_setting DROP CONSTRAINT FK_28E4CFFDD395B25E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE filter_setting DROP CONSTRAINT FK_28E4CFFD990BEA15
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE filter_setting DROP CONSTRAINT FK_28E4CFFDD0C9C2E9
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE comparator
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE criteria
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE filter
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE filter_setting
        SQL);
    }
}
