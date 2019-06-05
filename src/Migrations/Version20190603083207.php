<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190603083207 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE comment_viewer (id INT AUTO_INCREMENT NOT NULL, vacancy_viewer_user_id INT NOT NULL, candidate_link_id INT DEFAULT NULL, candidate_vacancy_id INT DEFAULT NULL, comment LONGTEXT NOT NULL, INDEX IDX_C94FF44754E7ADD1 (vacancy_viewer_user_id), INDEX IDX_C94FF4472275AFD0 (candidate_link_id), INDEX IDX_C94FF44771FC6FFA (candidate_vacancy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment_viewer ADD CONSTRAINT FK_C94FF44754E7ADD1 FOREIGN KEY (vacancy_viewer_user_id) REFERENCES vacancy_viewer_user (id)');
        $this->addSql('ALTER TABLE comment_viewer ADD CONSTRAINT FK_C94FF4472275AFD0 FOREIGN KEY (candidate_link_id) REFERENCES candidate_link (id)');
        $this->addSql('ALTER TABLE comment_viewer ADD CONSTRAINT FK_C94FF44771FC6FFA FOREIGN KEY (candidate_vacancy_id) REFERENCES candidate_vacancy (id)');
        $this->addSql('ALTER TABLE candidate_link ADD denial_interview LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE candidate_vacancy ADD denial_interview LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE comment_viewer');
        $this->addSql('ALTER TABLE candidate_link DROP denial_interview');
        $this->addSql('ALTER TABLE candidate_vacancy DROP denial_interview');
    }
}
