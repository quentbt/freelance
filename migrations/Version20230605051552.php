<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230605051552 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE achat (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, id_service_id INT DEFAULT NULL, INDEX IDX_26A9845679F37AE5 (id_user_id), INDEX IDX_26A9845648D62931 (id_service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A9845679F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A9845648D62931 FOREIGN KEY (id_service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD23E4DA9E5');
        $this->addSql('DROP INDEX IDX_E19D9AD23E4DA9E5 ON service');
        $this->addSql('ALTER TABLE service DROP historique_achat_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE achat DROP FOREIGN KEY FK_26A9845679F37AE5');
        $this->addSql('ALTER TABLE achat DROP FOREIGN KEY FK_26A9845648D62931');
        $this->addSql('DROP TABLE achat');
        $this->addSql('ALTER TABLE service ADD historique_achat_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD23E4DA9E5 FOREIGN KEY (historique_achat_id) REFERENCES historique_achat (id)');
        $this->addSql('CREATE INDEX IDX_E19D9AD23E4DA9E5 ON service (historique_achat_id)');
    }
}
