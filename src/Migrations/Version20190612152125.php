<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190612152125 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE candidate_link ADD conf_room_id INT DEFAULT NULL, ADD date_interview DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE candidate_link ADD CONSTRAINT FK_E85A153421F9803 FOREIGN KEY (conf_room_id) REFERENCES conf_room (id)');
        $this->addSql('CREATE INDEX IDX_E85A153421F9803 ON candidate_link (conf_room_id)');
        $this->addSql('ALTER TABLE candidate_vacancy ADD conf_room_id INT DEFAULT NULL, ADD date_interview DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE candidate_vacancy ADD CONSTRAINT FK_26E5C11C421F9803 FOREIGN KEY (conf_room_id) REFERENCES conf_room (id)');
        $this->addSql('CREATE INDEX IDX_26E5C11C421F9803 ON candidate_vacancy (conf_room_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE candidate_link DROP FOREIGN KEY FK_E85A153421F9803');
        $this->addSql('DROP INDEX IDX_E85A153421F9803 ON candidate_link');
        $this->addSql('ALTER TABLE candidate_link DROP conf_room_id, DROP date_interview');
        $this->addSql('ALTER TABLE candidate_vacancy DROP FOREIGN KEY FK_26E5C11C421F9803');
        $this->addSql('DROP INDEX IDX_26E5C11C421F9803 ON candidate_vacancy');
        $this->addSql('ALTER TABLE candidate_vacancy DROP conf_room_id, DROP date_interview');
    }
}
