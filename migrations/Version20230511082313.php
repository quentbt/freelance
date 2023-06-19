<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230511082313 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE messagerie ADD user_receveur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE messagerie ADD CONSTRAINT FK_14E8F60C107C2FB3 FOREIGN KEY (user_receveur_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_14E8F60C107C2FB3 ON messagerie (user_receveur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE messagerie DROP FOREIGN KEY FK_14E8F60C107C2FB3');
        $this->addSql('DROP INDEX IDX_14E8F60C107C2FB3 ON messagerie');
        $this->addSql('ALTER TABLE messagerie DROP user_receveur_id');
    }
}
