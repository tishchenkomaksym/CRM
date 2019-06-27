<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190627091732 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE employee_on_boarding_info (id INT AUTO_INCREMENT NOT NULL, candidate_id INT NOT NULL, full_name VARCHAR(100) NOT NULL, sex ENUM(\'Male\', \'Female\'), birthday DATETIME NOT NULL, maritalStatus ENUM(\'Single\', \'Married\', \'Divorced\'), children VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_537BE85291BD8781 (candidate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE employee_on_boarding_info ADD CONSTRAINT FK_537BE85291BD8781 FOREIGN KEY (candidate_id) REFERENCES candidate (id)');
        $this->addSql('ALTER TABLE vacancy CHANGE amount amount INT NOT NULL');
        $this->addSql('ALTER TABLE user_info ADD candidate_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_info ADD CONSTRAINT FK_B1087D9E91BD8781 FOREIGN KEY (candidate_id) REFERENCES candidate (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B1087D9E91BD8781 ON user_info (candidate_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE employee_on_boarding_info');
        $this->addSql('ALTER TABLE user_info DROP FOREIGN KEY FK_B1087D9E91BD8781');
        $this->addSql('DROP INDEX UNIQ_B1087D9E91BD8781 ON user_info');
        $this->addSql('ALTER TABLE user_info DROP candidate_id');
        $this->addSql('ALTER TABLE vacancy CHANGE amount amount VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
