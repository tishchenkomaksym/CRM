<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190509113532 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE candidate_vacancy (id INT AUTO_INCREMENT NOT NULL, candidate_id INT NOT NULL, vacancy_id INT NOT NULL, candidateFrom ENUM(\'vacancy\', \'hunting\',\'recommendation\'), received_cv VARCHAR(500) DEFAULT NULL, link_to_profile1 VARCHAR(255) DEFAULT NULL, link_to_profile2 VARCHAR(255) DEFAULT NULL, link_to_profile3 VARCHAR(255) DEFAULT NULL, link_to_profile4 VARCHAR(255) DEFAULT NULL, INDEX IDX_26E5C11C91BD8781 (candidate_id), INDEX IDX_26E5C11C433B78C4 (vacancy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE candidate_vacancy ADD CONSTRAINT FK_26E5C11C91BD8781 FOREIGN KEY (candidate_id) REFERENCES candidate (id)');
        $this->addSql('ALTER TABLE candidate_vacancy ADD CONSTRAINT FK_26E5C11C433B78C4 FOREIGN KEY (vacancy_id) REFERENCES vacancy (id)');
        $this->addSql('ALTER TABLE candidate DROP FOREIGN KEY FK_C8B28E44433B78C4');
        $this->addSql('DROP INDEX IDX_C8B28E44433B78C4 ON candidate');
        $this->addSql('ALTER TABLE candidate DROP vacancy_id, DROP link, DROP received_cv, CHANGE updated_date updated_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE vacancy CHANGE updated_date updated_date DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE candidate_vacancy');
        $this->addSql('ALTER TABLE candidate ADD vacancy_id INT DEFAULT NULL, ADD link VARCHAR(1000) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD received_cv VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE updated_date updated_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE candidate ADD CONSTRAINT FK_C8B28E44433B78C4 FOREIGN KEY (vacancy_id) REFERENCES vacancy (id)');
        $this->addSql('CREATE INDEX IDX_C8B28E44433B78C4 ON candidate (vacancy_id)');

        $this->addSql('ALTER TABLE vacancy CHANGE updated_date updated_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
