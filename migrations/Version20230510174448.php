<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230510174448 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, user_envoyeur_id INT DEFAULT NULL, user_receveur_id INT DEFAULT NULL, messagerie_id INT DEFAULT NULL, message LONGTEXT NOT NULL, INDEX IDX_B6BD307FEE8E6E13 (user_envoyeur_id), INDEX IDX_B6BD307F107C2FB3 (user_receveur_id), INDEX IDX_B6BD307F836C1031 (messagerie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messagerie (id INT AUTO_INCREMENT NOT NULL, messagerie_user_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_14E8F60CEC5080E9 (messagerie_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FEE8E6E13 FOREIGN KEY (user_envoyeur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F107C2FB3 FOREIGN KEY (user_receveur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F836C1031 FOREIGN KEY (messagerie_id) REFERENCES messagerie (id)');
        $this->addSql('ALTER TABLE messagerie ADD CONSTRAINT FK_14E8F60CEC5080E9 FOREIGN KEY (messagerie_user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FEE8E6E13');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F107C2FB3');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F836C1031');
        $this->addSql('ALTER TABLE messagerie DROP FOREIGN KEY FK_14E8F60CEC5080E9');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE messagerie');
    }
}
