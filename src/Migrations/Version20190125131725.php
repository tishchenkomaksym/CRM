<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190125131725 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE monthly_sdt DROP FOREIGN KEY FK_CC1D6D149D86650F');
        $this->addSql('DROP INDEX IDX_CC1D6D149D86650F ON monthly_sdt');
        $this->addSql('ALTER TABLE monthly_sdt CHANGE user_id_id user_id INT NOT NULL');
        $this->addSql(
            'ALTER TABLE monthly_sdt ADD CONSTRAINT FK_CC1D6D14A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)'
        );
        $this->addSql('CREATE INDEX IDX_CC1D6D14A76ED395 ON monthly_sdt (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE monthly_sdt DROP FOREIGN KEY FK_CC1D6D14A76ED395');
        $this->addSql('DROP INDEX IDX_CC1D6D14A76ED395 ON monthly_sdt');
        $this->addSql('ALTER TABLE monthly_sdt CHANGE user_id user_id_id INT NOT NULL');
        $this->addSql(
            'ALTER TABLE monthly_sdt ADD CONSTRAINT FK_CC1D6D149D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)'
        );
        $this->addSql('CREATE INDEX IDX_CC1D6D149D86650F ON monthly_sdt (user_id_id)');
    }
}
