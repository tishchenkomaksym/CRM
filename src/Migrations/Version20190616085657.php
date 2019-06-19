<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190616085657 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE candidate_manager_deny (id INT AUTO_INCREMENT NOT NULL, candidate_link_id INT DEFAULT NULL, candidate_vacancy_id INT DEFAULT NULL, impression LONGTEXT DEFAULT NULL, recruiter_reported TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_CDA437362275AFD0 (candidate_link_id), UNIQUE INDEX UNIQ_CDA4373671FC6FFA (candidate_vacancy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deny_reason_candidate (id INT AUTO_INCREMENT NOT NULL, deny_choice_candidate_id INT NOT NULL, candidate_offer_deny_id INT NOT NULL, INDEX IDX_77065C27CA58B8DB (deny_choice_candidate_id), INDEX IDX_77065C279CE7CE5D (candidate_offer_deny_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE candidate_offer_deny (id INT AUTO_INCREMENT NOT NULL, candidate_link_id INT DEFAULT NULL, candidate_vacancy_id INT DEFAULT NULL, no_suitable_reason LONGTEXT DEFAULT NULL, desired_salary LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_C584001B2275AFD0 (candidate_link_id), UNIQUE INDEX UNIQ_C584001B71FC6FFA (candidate_vacancy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE candidate_manager_approval (id INT AUTO_INCREMENT NOT NULL, team_id INT NOT NULL, candidate_link_id INT DEFAULT NULL, candidate_vacancy_id INT DEFAULT NULL, direction_enterpreneur LONGTEXT NOT NULL, level VARCHAR(255) NOT NULL, start_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', salary INT NOT NULL, work_place LONGTEXT NOT NULL, nickname VARCHAR(255) DEFAULT NULL, INDEX IDX_C24F2741296CD8AE (team_id), UNIQUE INDEX UNIQ_C24F27412275AFD0 (candidate_link_id), UNIQUE INDEX UNIQ_C24F274171FC6FFA (candidate_vacancy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deny_choice_candidate (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(500) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deny_choice_department (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(500) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deny_reason_department (id INT AUTO_INCREMENT NOT NULL, deny_choice_department_id INT NOT NULL, candidate_manager_deny_id INT NOT NULL, INDEX IDX_191965BA2565A80A (deny_choice_department_id), INDEX IDX_191965BA71B9B1A (candidate_manager_deny_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE candidate_manager_deny ADD CONSTRAINT FK_CDA437362275AFD0 FOREIGN KEY (candidate_link_id) REFERENCES candidate_link (id)');
        $this->addSql('ALTER TABLE candidate_manager_deny ADD CONSTRAINT FK_CDA4373671FC6FFA FOREIGN KEY (candidate_vacancy_id) REFERENCES candidate_vacancy (id)');
        $this->addSql('ALTER TABLE deny_reason_candidate ADD CONSTRAINT FK_77065C27CA58B8DB FOREIGN KEY (deny_choice_candidate_id) REFERENCES deny_choice_candidate (id)');
        $this->addSql('ALTER TABLE deny_reason_candidate ADD CONSTRAINT FK_77065C279CE7CE5D FOREIGN KEY (candidate_offer_deny_id) REFERENCES candidate_offer_deny (id)');
        $this->addSql('ALTER TABLE candidate_offer_deny ADD CONSTRAINT FK_C584001B2275AFD0 FOREIGN KEY (candidate_link_id) REFERENCES candidate_link (id)');
        $this->addSql('ALTER TABLE candidate_offer_deny ADD CONSTRAINT FK_C584001B71FC6FFA FOREIGN KEY (candidate_vacancy_id) REFERENCES candidate_vacancy (id)');
        $this->addSql('ALTER TABLE candidate_manager_approval ADD CONSTRAINT FK_C24F2741296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE candidate_manager_approval ADD CONSTRAINT FK_C24F27412275AFD0 FOREIGN KEY (candidate_link_id) REFERENCES candidate_link (id)');
        $this->addSql('ALTER TABLE candidate_manager_approval ADD CONSTRAINT FK_C24F274171FC6FFA FOREIGN KEY (candidate_vacancy_id) REFERENCES candidate_vacancy (id)');
        $this->addSql('ALTER TABLE deny_reason_department ADD CONSTRAINT FK_191965BA2565A80A FOREIGN KEY (deny_choice_department_id) REFERENCES deny_choice_department (id)');
        $this->addSql('ALTER TABLE deny_reason_department ADD CONSTRAINT FK_191965BA71B9B1A FOREIGN KEY (candidate_manager_deny_id) REFERENCES candidate_manager_deny (id)');
        $this->addSql('INSERT INTO deny_choice_department (id, name)
                        VALUES (1, \'Недостаточное количество опыта для данной позиции \'),
                               (2, \'Неумение использовать свои навыки\'),
                               (3, \'Неподходящие личные качества (нелояльность, колнфликтность и т.д.)\'),
                               (4, \'Слабое логическое мышление\'),
                               (5, \'Неподходящий опыт для позиции\')');
        $this->addSql('INSERT INTO deny_choice_candidate (id, name)
                        VALUES (1, \'Меньший доход, чем рассчитывал \'),
                               (2, \'Отсутствие соц.пакета (страховка, больничный, различные компенсации)\'),
                               (3, \'Неподходящий график\'),
                               (4, \'Несогласие/невозжность работать как ЧП (другая группа, долг в налоговой и т.д.)\'),
                               (5, \'Неудобное расположение офиса\'),
                               (6, \'Маленькое количество отпуска\'),
                               (7, \'Отзывы о компании\'),
                               (8, \'Отрицательное впечатление об интервью\'),
                               (9, \'Несоответствие навыков требованиям\'),
                               (10, \'Личные обстоятельства (форс-мажоры, болезнь и т.д.)\'),
                               (11, \'Контр-оффер от текущей компании\'),
                               (12, \'Долго ждал оффер\')
                               ');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE deny_reason_department DROP FOREIGN KEY FK_191965BA71B9B1A');
        $this->addSql('ALTER TABLE deny_reason_candidate DROP FOREIGN KEY FK_77065C279CE7CE5D');
        $this->addSql('ALTER TABLE deny_reason_candidate DROP FOREIGN KEY FK_77065C27CA58B8DB');
        $this->addSql('ALTER TABLE deny_reason_department DROP FOREIGN KEY FK_191965BA2565A80A');
        $this->addSql('DROP TABLE candidate_manager_deny');
        $this->addSql('DROP TABLE deny_reason_candidate');
        $this->addSql('DROP TABLE candidate_offer_deny');
        $this->addSql('DROP TABLE candidate_manager_approval');
        $this->addSql('DROP TABLE deny_choice_candidate');
        $this->addSql('DROP TABLE deny_choice_department');
        $this->addSql('DROP TABLE deny_reason_department');
    }
}
