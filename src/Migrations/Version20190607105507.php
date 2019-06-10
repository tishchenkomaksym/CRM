<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190607105507 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE department_team_sdt_view_rules DROP FOREIGN KEY FK_8420CE7D296CD8AE');
        $this->addSql('ALTER TABLE department_team_sdt_view_rules DROP FOREIGN KEY FK_8420CE7DAE80F5DF');
        $this->addSql('DROP INDEX IDX_8420CE7DAE80F5DF ON department_team_sdt_view_rules');
        $this->addSql('DROP INDEX IDX_8420CE7D296CD8AE ON department_team_sdt_view_rules');
        $this->addSql('ALTER TABLE department_team_sdt_view_rules ADD team VARCHAR(255) NOT NULL, ADD department VARCHAR(255) NOT NULL, DROP department_id, DROP team_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE department_team_sdt_view_rules ADD department_id INT NOT NULL, ADD team_id INT NOT NULL, DROP team, DROP department');
        $this->addSql('ALTER TABLE department_team_sdt_view_rules ADD CONSTRAINT FK_8420CE7D296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE department_team_sdt_view_rules ADD CONSTRAINT FK_8420CE7DAE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('CREATE INDEX IDX_8420CE7DAE80F5DF ON department_team_sdt_view_rules (department_id)');
        $this->addSql('CREATE INDEX IDX_8420CE7D296CD8AE ON department_team_sdt_view_rules (team_id)');
    }
}
