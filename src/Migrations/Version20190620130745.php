<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190620130745 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE qa_actual_skill_test_mark (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, qa_skill_test_id INT DEFAULT NULL, actual_points INT DEFAULT NULL, INDEX IDX_F19AE481A76ED395 (user_id), INDEX IDX_F19AE4811C33544F (qa_skill_test_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE qa_actual_skill_test_mark ADD CONSTRAINT FK_F19AE481A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE qa_actual_skill_test_mark ADD CONSTRAINT FK_F19AE4811C33544F FOREIGN KEY (qa_skill_test_id) REFERENCES qa_skill_test (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE qa_actual_skill_test_mark');
    }
}
