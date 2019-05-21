<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190520073127 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE candidate_link (id INT AUTO_INCREMENT NOT NULL, candidate_id INT NOT NULL, vacancy_link_id INT NOT NULL, candidateFrom ENUM(\'vacancy\', \'hunting\',\'recommendation\'), received_cv VARCHAR(500) DEFAULT NULL, comment_interest LONGTEXT DEFAULT NULL, denial_reason LONGTEXT DEFAULT NULL, candidateStatus ENUM(\'CV Received\',\'Candidates Interest is checked\',\'Waiting for response\',\'Approved for the interview\',\'Interview timing specification\',\'Waiting for interview\',\'Waiting for our final response\',\'Closed\'), INDEX IDX_E85A15391BD8781 (candidate_id), INDEX IDX_E85A1533AD5BEB7 (vacancy_link_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE candidate_link ADD CONSTRAINT FK_E85A15391BD8781 FOREIGN KEY (candidate_id) REFERENCES candidate (id)');
        $this->addSql('ALTER TABLE candidate_link ADD CONSTRAINT FK_E85A1533AD5BEB7 FOREIGN KEY (vacancy_link_id) REFERENCES vacancy_link (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE candidate_link');
    }
}
