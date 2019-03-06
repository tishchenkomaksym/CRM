<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190305170756 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    /**
     * @param Schema $schema
     * @throws \Doctrine\DBAL\DBALException
     */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql(
            'CREATE TABLE php_developer_level_test_technical_component (id INT AUTO_INCREMENT NOT NULL, php_developer_level_id INT NOT NULL, name VARCHAR(255) NOT NULL, jira_name VARCHAR(255) NOT NULL, required_hours INT NOT NULL, INDEX IDX_BCC0BF90AE035142 (php_developer_level_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'ALTER TABLE php_developer_level_test_technical_component ADD CONSTRAINT FK_BCC0BF90AE035142 FOREIGN KEY (php_developer_level_id) REFERENCES php_developer_level_test (id)'
        );
    }

    /**
     * @param Schema $schema
     * @throws \Doctrine\DBAL\DBALException
     */
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('DROP TABLE php_developer_level_test_technical_component');
    }
}
