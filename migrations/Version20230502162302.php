<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230502162302 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE discussion (id INT AUTO_INCREMENT NOT NULL, user_reception_id INT NOT NULL, message LONGTEXT NOT NULL, INDEX IDX_C0B9F90FFCD8A350 (user_reception_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE discussion ADD CONSTRAINT FK_C0B9F90FFCD8A350 FOREIGN KEY (user_reception_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE discussion DROP FOREIGN KEY FK_C0B9F90FFCD8A350');
        $this->addSql('DROP TABLE discussion');
    }
}
