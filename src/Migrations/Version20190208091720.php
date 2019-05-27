<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190208091720 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    /**
     * @param Schema $schema
     * @throws \Doctrine\DBAL\DBALException
     */
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE php_developer_manager_relation (id INT AUTO_INCREMENT NOT NULL, php_developer_id INT NOT NULL, manager_id INT NOT NULL, INDEX IDX_BC2B1C3B65F77421 (php_developer_id), INDEX IDX_BC2B1C3B783E3463 (manager_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE php_developer_manager_relation ADD CONSTRAINT FK_BC2B1C3B65F77421 FOREIGN KEY (php_developer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE php_developer_manager_relation ADD CONSTRAINT FK_BC2B1C3B783E3463 FOREIGN KEY (manager_id) REFERENCES user (id)');
    }

    /**
     * @param Schema $schema
     * @throws \Doctrine\DBAL\DBALException
     */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE php_developer_manager_relation');
    }
}
