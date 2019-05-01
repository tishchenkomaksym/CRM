<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190424080811 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vacancy ADD assignee_id INT DEFAULT NULL, DROP assignee');
        $this->addSql('ALTER TABLE vacancy ADD CONSTRAINT FK_A9346CBD59EC7D60 FOREIGN KEY (assignee_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_A9346CBD59EC7D60 ON vacancy (assignee_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vacancy DROP FOREIGN KEY FK_A9346CBD59EC7D60');
        $this->addSql('DROP INDEX IDX_A9346CBD59EC7D60 ON vacancy');
        $this->addSql('ALTER TABLE vacancy ADD assignee VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, DROP assignee_id');
    }
}
