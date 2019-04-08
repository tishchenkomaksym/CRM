<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190408074308 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE vacancy (id INT AUTO_INCREMENT NOT NULL, office_id INT NOT NULL, department_id INT NOT NULL, team_id INT DEFAULT NULL, position VARCHAR(255) NOT NULL, salary VARCHAR(255) NOT NULL, test VARCHAR(255) NOT NULL, english VARCHAR(255) NOT NULL, amount VARCHAR(255) NOT NULL, bonus VARCHAR(255) DEFAULT NULL, responsibilities LONGTEXT NOT NULL, requirements LONGTEXT NOT NULL, plus LONGTEXT DEFAULT NULL, reason LONGTEXT NOT NULL, INDEX IDX_A9346CBDFFA0C224 (office_id), INDEX IDX_A9346CBDAE80F5DF (department_id), INDEX IDX_A9346CBD296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vacancy ADD CONSTRAINT FK_A9346CBDFFA0C224 FOREIGN KEY (office_id) REFERENCES office (id)');
        $this->addSql('ALTER TABLE vacancy ADD CONSTRAINT FK_A9346CBDAE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE vacancy ADD CONSTRAINT FK_A9346CBD296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE user CHANGE create_date create_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE vacancy');
        $this->addSql('ALTER TABLE user CHANGE create_date create_date DATETIME DEFAULT NULL');
    }
}
