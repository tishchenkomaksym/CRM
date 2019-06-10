<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190607104005 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE department_team_sdt_view_rules_team (department_team_sdt_view_rules_id INT NOT NULL, team_id INT NOT NULL, INDEX IDX_69B770E0547FE4BF (department_team_sdt_view_rules_id), INDEX IDX_69B770E0296CD8AE (team_id), PRIMARY KEY(department_team_sdt_view_rules_id, team_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE department_team_sdt_view_rules_team ADD CONSTRAINT FK_69B770E0547FE4BF FOREIGN KEY (department_team_sdt_view_rules_id) REFERENCES department_team_sdt_view_rules (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE department_team_sdt_view_rules_team ADD CONSTRAINT FK_69B770E0296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE department_team_sdt_view_rules_team');
    }
}
