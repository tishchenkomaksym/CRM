<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190607115559 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE candidate_vacancy_history (id INT AUTO_INCREMENT NOT NULL, candidate_link_id INT DEFAULT NULL, candidate_vacancy_id INT DEFAULT NULL, updated_at DATETIME NOT NULL, candidateStatus ENUM(\'CV Received\',\'Candidate is interested in vacancy\', \'Candidate is waiting for approval\', \'Approved for the interview\',\'Interview timing specification\',\'Waiting for interview\',\'Waiting for our final response\',\'Closed by recrutier\',\'Closed by department manager\',\'Candidate declined proposition\'), INDEX IDX_1A8471F82275AFD0 (candidate_link_id), INDEX IDX_1A8471F871FC6FFA (candidate_vacancy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE candidate_vacancy_history ADD CONSTRAINT FK_1A8471F82275AFD0 FOREIGN KEY (candidate_link_id) REFERENCES candidate_link (id)');
        $this->addSql('ALTER TABLE candidate_vacancy_history ADD CONSTRAINT FK_1A8471F871FC6FFA FOREIGN KEY (candidate_vacancy_id) REFERENCES candidate_vacancy (id)');
        $this->addSql('ALTER TABLE candidate_link ADD created_by_id INT DEFAULT NULL, ADD created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE candidate_link ADD CONSTRAINT FK_E85A153B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_E85A153B03A8386 ON candidate_link (created_by_id)');
        $this->addSql('ALTER TABLE candidate_vacancy ADD created_by_id INT DEFAULT NULL, ADD created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE candidate_vacancy ADD CONSTRAINT FK_26E5C11CB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_26E5C11CB03A8386 ON candidate_vacancy (created_by_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE candidate_vacancy_history');
        $this->addSql('ALTER TABLE candidate_link DROP FOREIGN KEY FK_E85A153B03A8386');
        $this->addSql('DROP INDEX IDX_E85A153B03A8386 ON candidate_link');
        $this->addSql('ALTER TABLE candidate_link DROP created_by_id, DROP created_at');
        $this->addSql('ALTER TABLE candidate_vacancy DROP FOREIGN KEY FK_26E5C11CB03A8386');
        $this->addSql('DROP INDEX IDX_26E5C11CB03A8386 ON candidate_vacancy');
        $this->addSql('ALTER TABLE candidate_vacancy DROP created_by_id, DROP created_at');
    }
}
