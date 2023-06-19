<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230502162917 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE discussion ADD user_envoyeur_id INT NOT NULL');
        $this->addSql('ALTER TABLE discussion ADD CONSTRAINT FK_C0B9F90FEE8E6E13 FOREIGN KEY (user_envoyeur_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_C0B9F90FEE8E6E13 ON discussion (user_envoyeur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE discussion DROP FOREIGN KEY FK_C0B9F90FEE8E6E13');
        $this->addSql('DROP INDEX IDX_C0B9F90FEE8E6E13 ON discussion');
        $this->addSql('ALTER TABLE discussion DROP user_envoyeur_id');
    }
}
