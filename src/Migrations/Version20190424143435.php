<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190424143435 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vacancy_link ADD vacancy_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vacancy_link ADD CONSTRAINT FK_AE892718433B78C4 FOREIGN KEY (vacancy_id) REFERENCES vacancy (id)');
        $this->addSql('CREATE INDEX IDX_AE892718433B78C4 ON vacancy_link (vacancy_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vacancy_link DROP FOREIGN KEY FK_AE892718433B78C4');
        $this->addSql('DROP INDEX IDX_AE892718433B78C4 ON vacancy_link');
        $this->addSql('ALTER TABLE vacancy_link DROP vacancy_id');
    }
}
