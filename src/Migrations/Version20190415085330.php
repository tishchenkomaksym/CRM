<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190415085330 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE vacancy_viewer_user (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, permission_user_id INT NOT NULL, UNIQUE INDEX UNIQ_AE5E3CBDA76ED395 (user_id), UNIQUE INDEX UNIQ_AE5E3CBD907F32C2 (permission_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vacancy_viewer_user ADD CONSTRAINT FK_AE5E3CBDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE vacancy_viewer_user ADD CONSTRAINT FK_AE5E3CBD907F32C2 FOREIGN KEY (permission_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE vacancy ADD vacancy_viewer_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vacancy ADD CONSTRAINT FK_A9346CBD54E7ADD1 FOREIGN KEY (vacancy_viewer_user_id) REFERENCES vacancy_viewer_user (id)');
        $this->addSql('CREATE INDEX IDX_A9346CBD54E7ADD1 ON vacancy (vacancy_viewer_user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vacancy DROP FOREIGN KEY FK_A9346CBD54E7ADD1');
        $this->addSql('DROP TABLE vacancy_viewer_user');
        $this->addSql('DROP INDEX IDX_A9346CBD54E7ADD1 ON vacancy');
        $this->addSql('ALTER TABLE vacancy DROP vacancy_viewer_user_id');
    }
}
