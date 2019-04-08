<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190403082131 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        //$this->addSql('CREATE TABLE office (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');

        $this->addSql('CREATE TABLE department (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, office_id INTEGER NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\'');
        $this->addSql("INSERT INTO department (name, office_id) VALUES ('Development team', 1),('Maintenance Team', 1), ('Product development', 1), ('Display team', 7), 
          ('SEL team', 7), ('WFM', 4), ('VOICE', 4), ('Digital', 4), ('Accountant', 5), ('Billing', 5), ('Purchasing', 5), ('Wheels&Warehouse', 5), ('Бухгалтер', 6), ('Development team', 2), 
          ('Development team', 3), ('Maintenance', 2), ('Maintenance', 3), ('Maintenance', 4), ('Maintenance', 5), ('Product development', 2)
                        ");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE department');
    }
}
