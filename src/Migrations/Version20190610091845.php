<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190610091845 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE holiday (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE monthly_sdt (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, count DOUBLE PRECISION NOT NULL, create_date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', INDEX IDX_CC1D6D14A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, team_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, create_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE php_developer_level_test_passed (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, php_developer_level_test_id INT NOT NULL, INDEX IDX_F10E0D46A76ED395 (user_id), INDEX IDX_F10E0D46AB182490 (php_developer_level_test_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE php_developer_manager_relation (id INT AUTO_INCREMENT NOT NULL, php_developer_id INT NOT NULL, manager_id INT NOT NULL, INDEX IDX_BC2B1C3B65F77421 (php_developer_id), INDEX IDX_BC2B1C3B783E3463 (manager_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE php_developer_rise_request (id INT AUTO_INCREMENT NOT NULL, php_developer_id INT NOT NULL, approved TINYINT(1) NOT NULL, created_date DATE NOT NULL, INDEX IDX_5513A35E65F77421 (php_developer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE php_developer_start_time_and_date_value (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, effective_time DOUBLE PRECISION NOT NULL, effective_project_time DOUBLE PRECISION NOT NULL, create_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_6DEC2FBCA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE candidate (id INT AUTO_INCREMENT NOT NULL, photo VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, position VARCHAR(255) DEFAULT NULL, location VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, linked_in VARCHAR(255) DEFAULT NULL, facebook VARCHAR(255) DEFAULT NULL, salary VARCHAR(255) DEFAULT NULL, experience VARCHAR(255) DEFAULT NULL, education VARCHAR(255) DEFAULT NULL, employment VARCHAR(255) DEFAULT NULL, comment LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_date DATETIME DEFAULT NULL, surname VARCHAR(255) NOT NULL, link_to_cv VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vacancy (id INT AUTO_INCREMENT NOT NULL, office_id INT NOT NULL, department_id INT NOT NULL, team_id INT DEFAULT NULL, created_by_id INT NOT NULL, vacancy_viewer_user_id INT DEFAULT NULL, assignee_id INT DEFAULT NULL, approved_by_id INT DEFAULT NULL, assigned_by_id INT DEFAULT NULL, position VARCHAR(255) NOT NULL, salary VARCHAR(255) NOT NULL, test VARCHAR(255) NOT NULL, english VARCHAR(255) NOT NULL, amount VARCHAR(255) NOT NULL, bonus VARCHAR(255) DEFAULT NULL, responsibilities LONGTEXT NOT NULL, requirements LONGTEXT NOT NULL, plus LONGTEXT DEFAULT NULL, reason LONGTEXT NOT NULL, reason_denied LONGTEXT DEFAULT NULL, status ENUM(\'Approved\', \'Denied\',\'No assignee\',\'Issue have been assigned\',\'Search for a candidate(s) have been started\',\'Closed\'), created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', approve_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', assignee_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_date DATETIME DEFAULT NULL, INDEX IDX_A9346CBDFFA0C224 (office_id), INDEX IDX_A9346CBDAE80F5DF (department_id), INDEX IDX_A9346CBD296CD8AE (team_id), INDEX IDX_A9346CBDB03A8386 (created_by_id), INDEX IDX_A9346CBD54E7ADD1 (vacancy_viewer_user_id), INDEX IDX_A9346CBD59EC7D60 (assignee_id), INDEX IDX_A9346CBD2D234F6A (approved_by_id), INDEX IDX_A9346CBD6E6F1246 (assigned_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment_viewer (id INT AUTO_INCREMENT NOT NULL, vacancy_viewer_user_id INT NOT NULL, candidate_link_id INT DEFAULT NULL, candidate_vacancy_id INT DEFAULT NULL, comment LONGTEXT NOT NULL, INDEX IDX_C94FF44754E7ADD1 (vacancy_viewer_user_id), INDEX IDX_C94FF4472275AFD0 (candidate_link_id), INDEX IDX_C94FF44771FC6FFA (candidate_vacancy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vacancy_link (id INT AUTO_INCREMENT NOT NULL, vacancy_id INT DEFAULT NULL, link VARCHAR(255) NOT NULL, letter_text LONGTEXT DEFAULT NULL, INDEX IDX_AE892718433B78C4 (vacancy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE candidate_link (id INT AUTO_INCREMENT NOT NULL, candidate_id INT NOT NULL, vacancy_link_id INT NOT NULL, created_by_id INT NOT NULL, candidateFrom ENUM(\'vacancy\', \'hunting\',\'recommendation\'), received_cv VARCHAR(500) DEFAULT NULL, comment_interest LONGTEXT DEFAULT NULL, denial_reason LONGTEXT DEFAULT NULL, candidateStatus ENUM(\'CV Received\',\'Candidate is interested in vacancy\',\'Candidate is waiting for approval\',\'Approved for the interview\',\'Interview timing specification\',\'Waiting for interview\',\'Waiting for our final response\',\'Closed by recrutier\',\'Closed by department manager\',\'Candidate declined proposition\'), denial_interview LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_E85A15391BD8781 (candidate_id), INDEX IDX_E85A1533AD5BEB7 (vacancy_link_id), INDEX IDX_E85A153B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE candidate_vacancy (id INT AUTO_INCREMENT NOT NULL, candidate_id INT NOT NULL, vacancy_id INT NOT NULL, created_by_id INT NOT NULL, candidateFrom ENUM(\'vacancy\', \'hunting\',\'recommendation\'), received_cv VARCHAR(500) DEFAULT NULL, link_to_profile1 VARCHAR(255) DEFAULT NULL, link_to_profile2 VARCHAR(255) DEFAULT NULL, link_to_profile3 VARCHAR(255) DEFAULT NULL, link_to_profile4 VARCHAR(255) DEFAULT NULL, comment_interest LONGTEXT DEFAULT NULL, denial_reason LONGTEXT DEFAULT NULL, candidateStatus ENUM(\'CV Received\',\'Candidate is interested in vacancy\', \'Candidate is waiting for approval\', \'Approved for the interview\',\'Interview timing specification\',\'Waiting for interview\',\'Waiting for our final response\',\'Closed by recrutier\',\'Closed by department manager\',\'Candidate declined proposition\'), created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', denial_interview LONGTEXT DEFAULT NULL, INDEX IDX_26E5C11C91BD8781 (candidate_id), INDEX IDX_26E5C11C433B78C4 (vacancy_id), INDEX IDX_26E5C11CB03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sdtemail_assignee (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, email VARCHAR(255) NOT NULL, INDEX IDX_39D18007A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE salary_report_info (id INT AUTO_INCREMENT NOT NULL, create_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sdt (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, create_date DATETIME NOT NULL, count INT NOT NULL, acting VARCHAR(255) NOT NULL, report_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', at_own_expense TINYINT(1) NOT NULL, INDEX IDX_FF0F149DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sdt_archive (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, create_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', count INT NOT NULL, acting VARCHAR(255) NOT NULL, report_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_BD92D045A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_info (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, position VARCHAR(255) DEFAULT NULL, sub_team VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, skype VARCHAR(255) DEFAULT NULL, personal_email VARCHAR(255) DEFAULT NULL, sex ENUM(\'male\', \'female\'), birth_day DATE DEFAULT NULL, maritalStatus ENUM(\'single\', \'married\', \'divorced\'), children VARCHAR(255) DEFAULT NULL, location VARCHAR(255) DEFAULT NULL, salary INT DEFAULT NULL, UNIQUE INDEX UNIQ_B1087D9EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_php_developer_level_relation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, php_developer_level_id INT NOT NULL, create_date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', UNIQUE INDEX UNIQ_CD8827FA76ED395 (user_id), INDEX IDX_CD8827FAE035142 (php_developer_level_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE php_developer_level_test (id INT AUTO_INCREMENT NOT NULL, php_developer_level_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, link VARCHAR(255) NOT NULL, information LONGTEXT NOT NULL, INDEX IDX_6F318A7CAE035142 (php_developer_level_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vacancy_viewer_user (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, permission_user_id INT NOT NULL, UNIQUE INDEX UNIQ_AE5E3CBDA76ED395 (user_id), INDEX IDX_AE5E3CBD907F32C2 (permission_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, department_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_C4E0A61FAE80F5DF (department_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE qa_components (id INT AUTO_INCREMENT NOT NULL, team_id INT NOT NULL, name VARCHAR(255) NOT NULL, child_components VARCHAR(10000) NOT NULL, first_lvl_hours INT NOT NULL, second_lvl_hours INT NOT NULL, third_lvl_hours INT NOT NULL, four_lvl_hours INT NOT NULL, five_lvl_hours INT NOT NULL, INDEX IDX_3D65778D296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE office (id INT AUTO_INCREMENT NOT NULL, top_manager_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_74516B025F43BEF1 (top_manager_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE php_developer_level_hours_required (id INT AUTO_INCREMENT NOT NULL, php_developer_level_id INT NOT NULL, effective_time INT NOT NULL, effective_project_time INT NOT NULL, UNIQUE INDEX UNIQ_8CA186C6AE035142 (php_developer_level_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE department_team_sdt_view_rules (id INT AUTO_INCREMENT NOT NULL, team VARCHAR(255) NOT NULL, department VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE candidate_vacancy_history (id INT AUTO_INCREMENT NOT NULL, candidate_link_id INT DEFAULT NULL, candidate_vacancy_id INT DEFAULT NULL, updated_at DATETIME NOT NULL, candidateStatus ENUM(\'CV Received\',\'Candidate is interested in vacancy\', \'Candidate is waiting for approval\', \'Approved for the interview\',\'Interview timing specification\',\'Waiting for interview\',\'Waiting for our final response\',\'Closed by recrutier\',\'Closed by department manager\',\'Candidate declined proposition\'), INDEX IDX_1A8471F82275AFD0 (candidate_link_id), INDEX IDX_1A8471F871FC6FFA (candidate_vacancy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE php_developer_level_test_technical_component (id INT AUTO_INCREMENT NOT NULL, php_developer_level_id INT NOT NULL, name VARCHAR(255) NOT NULL, jira_name VARCHAR(255) NOT NULL, required_hours INT NOT NULL, INDEX IDX_BCC0BF90AE035142 (php_developer_level_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE php_developer_level (id INT AUTO_INCREMENT NOT NULL, next_level_id INT DEFAULT NULL, title VARCHAR(20) NOT NULL, UNIQUE INDEX UNIQ_D2EA608B67AB0749 (next_level_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE department (id INT AUTO_INCREMENT NOT NULL, office_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_CD1DE18AFFA0C224 (office_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE monthly_sdt ADD CONSTRAINT FK_CC1D6D14A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE php_developer_level_test_passed ADD CONSTRAINT FK_F10E0D46A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE php_developer_level_test_passed ADD CONSTRAINT FK_F10E0D46AB182490 FOREIGN KEY (php_developer_level_test_id) REFERENCES php_developer_level_test (id)');
        $this->addSql('ALTER TABLE php_developer_manager_relation ADD CONSTRAINT FK_BC2B1C3B65F77421 FOREIGN KEY (php_developer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE php_developer_manager_relation ADD CONSTRAINT FK_BC2B1C3B783E3463 FOREIGN KEY (manager_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE php_developer_rise_request ADD CONSTRAINT FK_5513A35E65F77421 FOREIGN KEY (php_developer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE php_developer_start_time_and_date_value ADD CONSTRAINT FK_6DEC2FBCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE vacancy ADD CONSTRAINT FK_A9346CBDFFA0C224 FOREIGN KEY (office_id) REFERENCES office (id)');
        $this->addSql('ALTER TABLE vacancy ADD CONSTRAINT FK_A9346CBDAE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE vacancy ADD CONSTRAINT FK_A9346CBD296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE vacancy ADD CONSTRAINT FK_A9346CBDB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE vacancy ADD CONSTRAINT FK_A9346CBD54E7ADD1 FOREIGN KEY (vacancy_viewer_user_id) REFERENCES vacancy_viewer_user (id)');
        $this->addSql('ALTER TABLE vacancy ADD CONSTRAINT FK_A9346CBD59EC7D60 FOREIGN KEY (assignee_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE vacancy ADD CONSTRAINT FK_A9346CBD2D234F6A FOREIGN KEY (approved_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE vacancy ADD CONSTRAINT FK_A9346CBD6E6F1246 FOREIGN KEY (assigned_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment_viewer ADD CONSTRAINT FK_C94FF44754E7ADD1 FOREIGN KEY (vacancy_viewer_user_id) REFERENCES vacancy_viewer_user (id)');
        $this->addSql('ALTER TABLE comment_viewer ADD CONSTRAINT FK_C94FF4472275AFD0 FOREIGN KEY (candidate_link_id) REFERENCES candidate_link (id)');
        $this->addSql('ALTER TABLE comment_viewer ADD CONSTRAINT FK_C94FF44771FC6FFA FOREIGN KEY (candidate_vacancy_id) REFERENCES candidate_vacancy (id)');
        $this->addSql('ALTER TABLE vacancy_link ADD CONSTRAINT FK_AE892718433B78C4 FOREIGN KEY (vacancy_id) REFERENCES vacancy (id)');
        $this->addSql('ALTER TABLE candidate_link ADD CONSTRAINT FK_E85A15391BD8781 FOREIGN KEY (candidate_id) REFERENCES candidate (id)');
        $this->addSql('ALTER TABLE candidate_link ADD CONSTRAINT FK_E85A1533AD5BEB7 FOREIGN KEY (vacancy_link_id) REFERENCES vacancy_link (id)');
        $this->addSql('ALTER TABLE candidate_link ADD CONSTRAINT FK_E85A153B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE candidate_vacancy ADD CONSTRAINT FK_26E5C11C91BD8781 FOREIGN KEY (candidate_id) REFERENCES candidate (id)');
        $this->addSql('ALTER TABLE candidate_vacancy ADD CONSTRAINT FK_26E5C11C433B78C4 FOREIGN KEY (vacancy_id) REFERENCES vacancy (id)');
        $this->addSql('ALTER TABLE candidate_vacancy ADD CONSTRAINT FK_26E5C11CB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE sdtemail_assignee ADD CONSTRAINT FK_39D18007A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE sdt ADD CONSTRAINT FK_FF0F149DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE sdt_archive ADD CONSTRAINT FK_BD92D045A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_info ADD CONSTRAINT FK_B1087D9EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_php_developer_level_relation ADD CONSTRAINT FK_CD8827FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_php_developer_level_relation ADD CONSTRAINT FK_CD8827FAE035142 FOREIGN KEY (php_developer_level_id) REFERENCES php_developer_level (id)');
        $this->addSql('ALTER TABLE php_developer_level_test ADD CONSTRAINT FK_6F318A7CAE035142 FOREIGN KEY (php_developer_level_id) REFERENCES php_developer_level (id)');
        $this->addSql('ALTER TABLE vacancy_viewer_user ADD CONSTRAINT FK_AE5E3CBDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE vacancy_viewer_user ADD CONSTRAINT FK_AE5E3CBD907F32C2 FOREIGN KEY (permission_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61FAE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE qa_components ADD CONSTRAINT FK_3D65778D296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE office ADD CONSTRAINT FK_74516B025F43BEF1 FOREIGN KEY (top_manager_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE php_developer_level_hours_required ADD CONSTRAINT FK_8CA186C6AE035142 FOREIGN KEY (php_developer_level_id) REFERENCES php_developer_level (id)');
        $this->addSql('ALTER TABLE candidate_vacancy_history ADD CONSTRAINT FK_1A8471F82275AFD0 FOREIGN KEY (candidate_link_id) REFERENCES candidate_link (id)');
        $this->addSql('ALTER TABLE candidate_vacancy_history ADD CONSTRAINT FK_1A8471F871FC6FFA FOREIGN KEY (candidate_vacancy_id) REFERENCES candidate_vacancy (id)');
        $this->addSql('ALTER TABLE php_developer_level_test_technical_component ADD CONSTRAINT FK_BCC0BF90AE035142 FOREIGN KEY (php_developer_level_id) REFERENCES php_developer_level_test (id)');
        $this->addSql('ALTER TABLE php_developer_level ADD CONSTRAINT FK_D2EA608B67AB0749 FOREIGN KEY (next_level_id) REFERENCES php_developer_level (id)');
        $this->addSql('ALTER TABLE department ADD CONSTRAINT FK_CD1DE18AFFA0C224 FOREIGN KEY (office_id) REFERENCES office (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE monthly_sdt DROP FOREIGN KEY FK_CC1D6D14A76ED395');
        $this->addSql('ALTER TABLE php_developer_level_test_passed DROP FOREIGN KEY FK_F10E0D46A76ED395');
        $this->addSql('ALTER TABLE php_developer_manager_relation DROP FOREIGN KEY FK_BC2B1C3B65F77421');
        $this->addSql('ALTER TABLE php_developer_manager_relation DROP FOREIGN KEY FK_BC2B1C3B783E3463');
        $this->addSql('ALTER TABLE php_developer_rise_request DROP FOREIGN KEY FK_5513A35E65F77421');
        $this->addSql('ALTER TABLE php_developer_start_time_and_date_value DROP FOREIGN KEY FK_6DEC2FBCA76ED395');
        $this->addSql('ALTER TABLE vacancy DROP FOREIGN KEY FK_A9346CBDB03A8386');
        $this->addSql('ALTER TABLE vacancy DROP FOREIGN KEY FK_A9346CBD59EC7D60');
        $this->addSql('ALTER TABLE vacancy DROP FOREIGN KEY FK_A9346CBD2D234F6A');
        $this->addSql('ALTER TABLE vacancy DROP FOREIGN KEY FK_A9346CBD6E6F1246');
        $this->addSql('ALTER TABLE candidate_link DROP FOREIGN KEY FK_E85A153B03A8386');
        $this->addSql('ALTER TABLE candidate_vacancy DROP FOREIGN KEY FK_26E5C11CB03A8386');
        $this->addSql('ALTER TABLE sdtemail_assignee DROP FOREIGN KEY FK_39D18007A76ED395');
        $this->addSql('ALTER TABLE sdt DROP FOREIGN KEY FK_FF0F149DA76ED395');
        $this->addSql('ALTER TABLE sdt_archive DROP FOREIGN KEY FK_BD92D045A76ED395');
        $this->addSql('ALTER TABLE user_info DROP FOREIGN KEY FK_B1087D9EA76ED395');
        $this->addSql('ALTER TABLE user_php_developer_level_relation DROP FOREIGN KEY FK_CD8827FA76ED395');
        $this->addSql('ALTER TABLE vacancy_viewer_user DROP FOREIGN KEY FK_AE5E3CBDA76ED395');
        $this->addSql('ALTER TABLE vacancy_viewer_user DROP FOREIGN KEY FK_AE5E3CBD907F32C2');
        $this->addSql('ALTER TABLE office DROP FOREIGN KEY FK_74516B025F43BEF1');
        $this->addSql('ALTER TABLE candidate_link DROP FOREIGN KEY FK_E85A15391BD8781');
        $this->addSql('ALTER TABLE candidate_vacancy DROP FOREIGN KEY FK_26E5C11C91BD8781');
        $this->addSql('ALTER TABLE vacancy_link DROP FOREIGN KEY FK_AE892718433B78C4');
        $this->addSql('ALTER TABLE candidate_vacancy DROP FOREIGN KEY FK_26E5C11C433B78C4');
        $this->addSql('ALTER TABLE candidate_link DROP FOREIGN KEY FK_E85A1533AD5BEB7');
        $this->addSql('ALTER TABLE comment_viewer DROP FOREIGN KEY FK_C94FF4472275AFD0');
        $this->addSql('ALTER TABLE candidate_vacancy_history DROP FOREIGN KEY FK_1A8471F82275AFD0');
        $this->addSql('ALTER TABLE comment_viewer DROP FOREIGN KEY FK_C94FF44771FC6FFA');
        $this->addSql('ALTER TABLE candidate_vacancy_history DROP FOREIGN KEY FK_1A8471F871FC6FFA');
        $this->addSql('ALTER TABLE php_developer_level_test_passed DROP FOREIGN KEY FK_F10E0D46AB182490');
        $this->addSql('ALTER TABLE php_developer_level_test_technical_component DROP FOREIGN KEY FK_BCC0BF90AE035142');
        $this->addSql('ALTER TABLE vacancy DROP FOREIGN KEY FK_A9346CBD54E7ADD1');
        $this->addSql('ALTER TABLE comment_viewer DROP FOREIGN KEY FK_C94FF44754E7ADD1');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649296CD8AE');
        $this->addSql('ALTER TABLE vacancy DROP FOREIGN KEY FK_A9346CBD296CD8AE');
        $this->addSql('ALTER TABLE qa_components DROP FOREIGN KEY FK_3D65778D296CD8AE');
        $this->addSql('ALTER TABLE vacancy DROP FOREIGN KEY FK_A9346CBDFFA0C224');
        $this->addSql('ALTER TABLE department DROP FOREIGN KEY FK_CD1DE18AFFA0C224');
        $this->addSql('ALTER TABLE user_php_developer_level_relation DROP FOREIGN KEY FK_CD8827FAE035142');
        $this->addSql('ALTER TABLE php_developer_level_test DROP FOREIGN KEY FK_6F318A7CAE035142');
        $this->addSql('ALTER TABLE php_developer_level_hours_required DROP FOREIGN KEY FK_8CA186C6AE035142');
        $this->addSql('ALTER TABLE php_developer_level DROP FOREIGN KEY FK_D2EA608B67AB0749');
        $this->addSql('ALTER TABLE vacancy DROP FOREIGN KEY FK_A9346CBDAE80F5DF');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61FAE80F5DF');
        $this->addSql('DROP TABLE holiday');
        $this->addSql('DROP TABLE monthly_sdt');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE php_developer_level_test_passed');
        $this->addSql('DROP TABLE php_developer_manager_relation');
        $this->addSql('DROP TABLE php_developer_rise_request');
        $this->addSql('DROP TABLE php_developer_start_time_and_date_value');
        $this->addSql('DROP TABLE candidate');
        $this->addSql('DROP TABLE vacancy');
        $this->addSql('DROP TABLE comment_viewer');
        $this->addSql('DROP TABLE vacancy_link');
        $this->addSql('DROP TABLE candidate_link');
        $this->addSql('DROP TABLE candidate_vacancy');
        $this->addSql('DROP TABLE sdtemail_assignee');
        $this->addSql('DROP TABLE salary_report_info');
        $this->addSql('DROP TABLE sdt');
        $this->addSql('DROP TABLE sdt_archive');
        $this->addSql('DROP TABLE user_info');
        $this->addSql('DROP TABLE user_php_developer_level_relation');
        $this->addSql('DROP TABLE php_developer_level_test');
        $this->addSql('DROP TABLE vacancy_viewer_user');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE qa_components');
        $this->addSql('DROP TABLE office');
        $this->addSql('DROP TABLE php_developer_level_hours_required');
        $this->addSql('DROP TABLE department_team_sdt_view_rules');
        $this->addSql('DROP TABLE candidate_vacancy_history');
        $this->addSql('DROP TABLE php_developer_level_test_technical_component');
        $this->addSql('DROP TABLE php_developer_level');
        $this->addSql('DROP TABLE department');
    }
}
