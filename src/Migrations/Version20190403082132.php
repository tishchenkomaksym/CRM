<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190403082132 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, department_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB COMMENT = \'\'');
        $this->addSql("INSERT INTO team (name, department_id) VALUES ('Display', 1), ('Create Team', 1), ('SELL Team', 1), ('NOC Team', 1),
            ('Admins', 2), ('HR', 2), ('Security', 2), ('PPC Team', 3), ('Display team', 14), ('Create team', 14), ('SELL team', 14), ('NOC team', 14),
            ('Data Mining Team', 14), ('WEB UI Team', 14), ('Import', 20), ('PM', 20), ('Design', 20), ('PQA', 20), ('Massload', 20), ('PPC Team', 20), ('Secondary', 20),
            ('Moto Team', 20), ('SEO', 2), ('Wheels&Tires Team', 20), ('Admins', 16), ('HR', 16), ('Security', 16), ('Accounting Team', 16), ('Display team', 15), 
            ('SELL team', 15), ('Admins', 17), ('HR', 17), ('Security', 17), ('Admins', 18), ('HR', 18), ('Security', 18), ('Admins', 19), ('HR', 19), ('Security', 19)                     
               ");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE team');
    }
}



