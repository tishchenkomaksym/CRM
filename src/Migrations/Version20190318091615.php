<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190318091615 extends AbstractMigration
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

        $this->addSql('ALTER TABLE php_developer_start_time_and_date_value ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE php_developer_start_time_and_date_value ADD CONSTRAINT FK_6DEC2FBCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6DEC2FBCA76ED395 ON php_developer_start_time_and_date_value (user_id)');
    }

    /**
     * @param Schema $schema
     * @throws \Doctrine\DBAL\DBALException
     */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE php_developer_start_time_and_date_value DROP FOREIGN KEY FK_6DEC2FBCA76ED395');
        $this->addSql('DROP INDEX UNIQ_6DEC2FBCA76ED395 ON php_developer_start_time_and_date_value');
        $this->addSql('ALTER TABLE php_developer_start_time_and_date_value DROP user_id');
    }
}
