<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190211093419 extends AbstractMigration
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
            'INSERT INTO user (email, roles, password) VALUES (\'accountmanager@onyx.com\', \'["ROLE_USER", "ROLE_ACCOUNT_MANAGER"]\', \'$argon2i$v=19$m=1024,t=2,p=2$WWM3dEhNeUtZTTRFT0I4Mw$ZPoR+cHVSedUlFI1RRsIBkIexZFxOzIU4LUWCntPlCM\');'
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

        $this->addSql('delete from user where email=\'accountmanager@onyx.com\'');
    }
}
