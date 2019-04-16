<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190409125931 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE office ADD top_manager_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE office ADD CONSTRAINT FK_74516B025F43BEF1 FOREIGN KEY (top_manager_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_74516B025F43BEF1 ON office (top_manager_id)');

        $this->addSql('INSERT INTO user (id, email, roles, password, name, create_date)
VALUES (101, \'topManager1@onyx.com\', \'[
  "ROLE_USER",
  "ROLE_TOP_MANAGER"
]\', \'$argon2i$v=19$m=1024,t=2,p=2$cWRKeFZ6LmdWVVpudEZSWQ$f6vptC22HNlOrQW7dJ1AdKmfZAW2Q0pNBGlhWNkULFk\', \'\',
        \'2019-04-05 10:38:20\'),
        (102, \'topManager2@onyx.com\', \'[
  "ROLE_USER",
  "ROLE_TOP_MANAGER"
]\', \'$argon2i$v=19$m=1024,t=2,p=2$cWRKeFZ6LmdWVVpudEZSWQ$f6vptC22HNlOrQW7dJ1AdKmfZAW2Q0pNBGlhWNkULFk\', \'\',
        \'2019-04-05 10:40:26\'),
        (103, \'topManager3@onyx.com\', \'[
  "ROLE_USER",
  "ROLE_TOP_MANAGER"
]\', \'$argon2i$v=19$m=1024,t=2,p=2$cWRKeFZ6LmdWVVpudEZSWQ$f6vptC22HNlOrQW7dJ1AdKmfZAW2Q0pNBGlhWNkULFk\', \'\',
        \'2019-04-05 10:41:26\'),
        (104, \'topManager4@onyx.com\', \'[
  "ROLE_USER",
  "ROLE_TOP_MANAGER"
]\', \'$argon2i$v=19$m=1024,t=2,p=2$cWRKeFZ6LmdWVVpudEZSWQ$f6vptC22HNlOrQW7dJ1AdKmfZAW2Q0pNBGlhWNkULFk\', \'\',
        \'2019-04-05 10:59:26\')');

        $this->addSql('UPDATE office SET top_manager_id = 101 where id = 1');
        $this->addSql('UPDATE office SET top_manager_id = 101 where id = 1');
        $this->addSql('UPDATE office SET top_manager_id = 102 where id = 1');
        $this->addSql('UPDATE office SET top_manager_id = 103 where id = 1');
        $this->addSql('UPDATE office SET top_manager_id = 101 where id = 1');
        $this->addSql('UPDATE office SET top_manager_id = 101 where id = 1');
        $this->addSql('UPDATE office SET top_manager_id = 101 where name = Dormashina (Nikolaev, Ukraine)');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE office DROP FOREIGN KEY FK_74516B025F43BEF1');
        $this->addSql('DROP INDEX IDX_74516B025F43BEF1 ON office');
        $this->addSql('ALTER TABLE office DROP top_manager_id');
    }
}
