<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190620123147 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE qa_required_skill_test_mark (id INT AUTO_INCREMENT NOT NULL, qa_level_id INT NOT NULL, test_id INT NOT NULL, required_points INT DEFAULT NULL, INDEX IDX_5B831AE0FD7649F8 (qa_level_id), INDEX IDX_5B831AE01E5D0459 (test_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE qa_required_skill_test_mark ADD CONSTRAINT FK_5B831AE0FD7649F8 FOREIGN KEY (qa_level_id) REFERENCES php_developer_level (id)');
        $this->addSql('ALTER TABLE qa_required_skill_test_mark ADD CONSTRAINT FK_5B831AE01E5D0459 FOREIGN KEY (test_id) REFERENCES qa_skill_test (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE qa_required_skill_test_mark');
    }
}
