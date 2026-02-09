<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260209161009 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE etape_format (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(80) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE poste (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(80) NOT NULL, tva DOUBLE PRECISION DEFAULT NULL, equipe SMALLINT DEFAULT NULL, prestataire SMALLINT DEFAULT NULL, ordre SMALLINT DEFAULT NULL, archive SMALLINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE etape_format');
        $this->addSql('DROP TABLE poste');
    }
}
