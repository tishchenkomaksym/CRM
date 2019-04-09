<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190408132401 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE department (id INT AUTO_INCREMENT NOT NULL, office_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_CD1DE18AFFA0C224 (office_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, department_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_C4E0A61FAE80F5DF (department_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE office (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vacancy (id INT AUTO_INCREMENT NOT NULL, office_id INT NOT NULL, department_id INT NOT NULL, team_id INT DEFAULT NULL, position VARCHAR(255) NOT NULL, salary VARCHAR(255) NOT NULL, test VARCHAR(255) NOT NULL, english VARCHAR(255) NOT NULL, amount VARCHAR(255) NOT NULL, bonus VARCHAR(255) DEFAULT NULL, responsibilities LONGTEXT NOT NULL, requirements LONGTEXT NOT NULL, plus LONGTEXT DEFAULT NULL, reason LONGTEXT NOT NULL, INDEX IDX_A9346CBDFFA0C224 (office_id), INDEX IDX_A9346CBDAE80F5DF (department_id), INDEX IDX_A9346CBD296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE department ADD CONSTRAINT FK_CD1DE18AFFA0C224 FOREIGN KEY (office_id) REFERENCES office (id)');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61FAE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE vacancy ADD CONSTRAINT FK_A9346CBDFFA0C224 FOREIGN KEY (office_id) REFERENCES office (id)');
        $this->addSql('ALTER TABLE vacancy ADD CONSTRAINT FK_A9346CBDAE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE vacancy ADD CONSTRAINT FK_A9346CBD296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('INSERT INTO office (name) VALUES (\'Lustdorfska road (Odessa, Ukraine)\'),
                (\'Buznika (Nikolaev, Ukraine)\'),(\'Mechnikova (Kiev, Ukraine)\'),
                (\'Chigrina (Nikolaev, Ukraine)\'),(\'Spasskaya (Nikolaev, Ukraine)\'),
            (\'Dormashina (Nikolaev, Ukraine)\'),(\'Gomel (Belarus)\')                   
        ');
        $this->addSql("INSERT INTO department (name, office_id) VALUES ('Development team', 1),('Maintenance Team', 1), ('Product development', 1), ('Display team', 7), 
          ('SEL team', 7), ('WFM', 4), ('VOICE', 4), ('Digital', 4), ('Accountant', 5), ('Billing', 5), ('Purchasing', 5), ('Wheels&Warehouse', 5), ('Бухгалтер', 6), ('Development team', 2), 
          ('Development team', 3), ('Maintenance', 2), ('Maintenance', 3), ('Maintenance', 4), ('Maintenance', 5), ('Product development', 2)
                        ");
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

        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61FAE80F5DF');
        $this->addSql('ALTER TABLE vacancy DROP FOREIGN KEY FK_A9346CBDAE80F5DF');
        $this->addSql('ALTER TABLE vacancy DROP FOREIGN KEY FK_A9346CBD296CD8AE');
        $this->addSql('ALTER TABLE department DROP FOREIGN KEY FK_CD1DE18AFFA0C224');
        $this->addSql('ALTER TABLE vacancy DROP FOREIGN KEY FK_A9346CBDFFA0C224');
        $this->addSql('DROP TABLE department');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE office');
        $this->addSql('DROP TABLE vacancy');
    }
}
