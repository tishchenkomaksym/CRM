<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190211093418 extends AbstractMigration
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

        $this->addSql('ALTER TABLE php_developer_level ADD next_level_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE php_developer_level ADD CONSTRAINT FK_D2EA608B67AB0749 FOREIGN KEY (next_level_id) REFERENCES php_developer_level (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D2EA608B67AB0749 ON php_developer_level (next_level_id)');
    }

    /**
     * @param Schema $schema
     * @throws \Doctrine\DBAL\DBALException
     */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE php_developer_level DROP FOREIGN KEY FK_D2EA608B67AB0749');
        $this->addSql('DROP INDEX UNIQ_D2EA608B67AB0749 ON php_developer_level');
        $this->addSql('ALTER TABLE php_developer_level DROP next_level_id');
    }
}
