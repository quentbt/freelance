<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230510190758 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE messagerie DROP INDEX UNIQ_14E8F60CEC5080E9, ADD INDEX IDX_14E8F60CEC5080E9 (messagerie_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE messagerie DROP INDEX IDX_14E8F60CEC5080E9, ADD UNIQUE INDEX UNIQ_14E8F60CEC5080E9 (messagerie_user_id)');
    }
}
