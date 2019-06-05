<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190603124735 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE candidate_vacancy ADD candidateStatus ENUM(\'CV Received\',\'Candidate is interested in vacancy\', \'Candidate is waiting for approval\', \'Approved for the interview\',\'Interview timing specification\',\'Waiting for interview\',\'Waiting for our final response\',\'Closed by recrutier\',\'Closed by department manager\',\'Candidate declined proposition\')');
        $this->addSql('ALTER TABLE candidate_link ADD candidateStatus ENUM(\'CV Received\',\'Candidate is interested in vacancy\',\'Candidate is waiting for approval\',\'Approved for the interview\',\'Interview timing specification\',\'Waiting for interview\',\'Waiting for our final response\',\'Closed by recrutier\',\'Closed by department manager\',\'Candidate declined proposition\')');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE candidate_link DROP candidateStatus');
        $this->addSql('ALTER TABLE candidate_vacancy DROP candidateStatus');
    }
}
