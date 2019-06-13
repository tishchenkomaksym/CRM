<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190610093628 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE conf_room (id INT AUTO_INCREMENT NOT NULL, office_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_669016BAFFA0C224 (office_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE conf_room ADD CONSTRAINT FK_669016BAFFA0C224 FOREIGN KEY (office_id) REFERENCES office (id)');
        $this->addSql('INSERT INTO conf_room (id, name, office_id)
                        VALUES (1, \'Конф. рум Одесса \', 1),
                               (2, \'Конф. рум Одесса (стекляшка)\', 1),
                               (3, \'Конф. рум 1 Бузника\', 2),
                               (4, \'Конф. рум 2 Бузника\', 2),
                               (5, \'Игровая комната Бузника\', 2),
                               (6, \'Новая кухня Бузника\', 2),
                               (7, \'Конф. рум Киев\', 3),
                               (8, \'Конф. рум Спасская\', 5),
                               (9, \'Конф. рум Спасская\', 5),
                               (10, \'Конф. рум Чигрина\', 4)');
        }


    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE conf_room');
    }
}
