<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190425140947 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE candidate (id INT AUTO_INCREMENT NOT NULL, photo VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, position VARCHAR(255) DEFAULT NULL, location VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, linked_in VARCHAR(255) DEFAULT NULL, facebook VARCHAR(255) DEFAULT NULL, salary VARCHAR(255) DEFAULT NULL, experience VARCHAR(255) DEFAULT NULL, education VARCHAR(255) DEFAULT NULL, employment VARCHAR(255) DEFAULT NULL, comment LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE candidate_vacancy (candidate_id INT NOT NULL, vacancy_id INT NOT NULL, INDEX IDX_26E5C11C91BD8781 (candidate_id), INDEX IDX_26E5C11C433B78C4 (vacancy_id), PRIMARY KEY(candidate_id, vacancy_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE candidate_vacancy ADD CONSTRAINT FK_26E5C11C91BD8781 FOREIGN KEY (candidate_id) REFERENCES candidate (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE candidate_vacancy ADD CONSTRAINT FK_26E5C11C433B78C4 FOREIGN KEY (vacancy_id) REFERENCES vacancy (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE candidate_vacancy DROP FOREIGN KEY FK_26E5C11C91BD8781');
        $this->addSql('DROP TABLE candidate');
        $this->addSql('DROP TABLE candidate_vacancy');
    }
}
