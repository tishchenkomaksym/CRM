<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190415064703 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vacancy ADD created_by_id INT NOT NULL, ADD created_at DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', ADD assignee VARCHAR(255) DEFAULT NULL, ADD approve_date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\'');
        $this->addSql('ALTER TABLE vacancy ADD CONSTRAINT FK_A9346CBDB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_A9346CBDB03A8386 ON vacancy (created_by_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vacancy DROP FOREIGN KEY FK_A9346CBDB03A8386');
        $this->addSql('DROP INDEX IDX_A9346CBDB03A8386 ON vacancy');
        $this->addSql('ALTER TABLE vacancy DROP created_by_id, DROP created_at, DROP assignee, DROP approve_date, DROP user_viewer');
    }
}
