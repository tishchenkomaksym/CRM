<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190614113204 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE qa_user_manager_relation (id INT AUTO_INCREMENT NOT NULL, qa_user_id INT NOT NULL, qa_manager_id INT NOT NULL, INDEX IDX_C08E23471DFEB6 (qa_user_id), INDEX IDX_C08E234AB13B613 (qa_manager_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE qa_user_manager_relation ADD CONSTRAINT FK_C08E23471DFEB6 FOREIGN KEY (qa_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE qa_user_manager_relation ADD CONSTRAINT FK_C08E234AB13B613 FOREIGN KEY (qa_manager_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE qa_user_manager_relation');
    }
}
