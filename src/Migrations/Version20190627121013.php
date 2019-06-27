<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190627121013 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE candidate ADD received_cv VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE employee_on_boarding_info CHANGE sex sex ENUM(\'Male\', \'Female\'), CHANGE maritalStatus maritalStatus ENUM(\'Single\', \'Married\', \'Divorced\')');
        $this->addSql('ALTER TABLE qa_skill_test DROP type');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE candidate DROP received_cv');
        $this->addSql('ALTER TABLE employee_on_boarding_info CHANGE sex sex VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE maritalStatus maritalStatus VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE qa_skill_test ADD type VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
