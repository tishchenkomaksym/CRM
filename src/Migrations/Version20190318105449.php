<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190318105449 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('INSERT INTO user (id, email, roles, password, name, create_date)
VALUES (5, \'ivan.melnichuk@onyx.com\', \'[
  "ROLE_USER",
  "ROLE_SDT_REQUEST",
  "ROLE_PHP_DEVELOPER"
]\', \'$argon2i$v=19$m=1024,t=2,p=2$TnhBTW1VTXVTWUNjazM5SQ$SUPDdpLXqhKizdsFF55cY07Y9exHUs4OkaIe/OgZDP8\', \'\',
        \'2019-04-05 10:38:06\'),
       (6, \'ivan.melnichuk1@onyx.com\', \'[
         "ROLE_USER",
         "ROLE_SDT_REQUEST",
         "ROLE_PHP_DEVELOPER"
       ]\', \'$argon2i$v=19$m=1024,t=2,p=2$M1EubG9jZjcxUXh5Vjk4QQ$sGAqmlyXyo3trqWYD/a/M7uJOkGxM9+ardGrwzdldaw\',
        \'Ivan Melnychuk\', \'2019-04-05 10:38:06\'),
       (7, \'juniorPM@onyx.com\', \'[
         "ROLE_USER",
         "ROLE_SDT_REQUEST",
         "ROLE_PHP_MANAGER"
       ]\', \'$argon2i$v=19$m=1024,t=2,p=2$MzA3UGVmeUYzZFVPQUlZZw$j4J0IczDrkyLrmhPEt34si7Vc2G4YEqhCz/9m4+xbUw\', \'\',
        \'2019-04-05 10:38:06\'),
       (8, \'recrutier@onyx.com\', \'[
         "ROLE_USER",
         "ROLE_RECRUITER"
       ]\', \'$argon2i$v=19$m=1024,t=2,p=2$SGhFY2cuaGtoQTBXSXhlRQ$0urxSUuwwPoyAIm1iYQPyOXlfHO1CqGYwt4ClpCE8xA\',
        \'Recruter\', \'2019-04-05 10:38:06\'),
       (10, \'departmentmanager@onyx.com\', \'[
         "ROLE_USER",
         "ROLE_RECRUITING_DEPARTMENT_MANAGER"
       ]\', \'$argon2i$v=19$m=1024,t=2,p=2$cWRKeFZ6LmdWVVpudEZSWQ$f6vptC22HNlOrQW7dJ1AdKmfZAW2Q0pNBGlhWNkULFk\',
        \'Department manager\', \'2019-04-05 10:38:06\'),
       (11, \'hr@onyx.com\', \'[
         "ROLE_USER",
         "ROLE_HR",
         "ROLE_MANAGE_HOLIDAYS",
         "ROLE_MANAGE_MONTHLY_SDT"
       ]\', \'$argon2i$v=19$m=1024,t=2,p=2$bmlLT2tZcDB2dWsvUTJTdQ$qvk+I6GqqGU3r2gAVqQWWoRZJSo4QQeDduVrY/y15e0\', \'\',
        \'2019-04-05 10:38:06\')');

        $this->addSql('ALTER TABLE php_developer_start_time_and_date_value CHANGE сreate_date create_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE php_developer_start_time_and_date_value CHANGE create_date сreate_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
