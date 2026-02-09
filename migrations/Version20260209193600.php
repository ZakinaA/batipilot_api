<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260209193600 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chantier (id INT AUTO_INCREMENT NOT NULL, adresse VARCHAR(120) DEFAULT NULL, copos VARCHAR(5) DEFAULT NULL, ville VARCHAR(120) DEFAULT NULL, date_debut_prevue DATE DEFAULT NULL, date_demarrage DATE DEFAULT NULL, date_reception DATE DEFAULT NULL, date_fin DATE DEFAULT NULL, surface_plancher DOUBLE PRECISION DEFAULT NULL, surface_habitable DOUBLE PRECISION DEFAULT NULL, distance_depot INT DEFAULT NULL, temps_trajet INT DEFAULT NULL, coefficient DOUBLE PRECISION DEFAULT NULL, alerte VARCHAR(255) DEFAULT NULL, archive SMALLINT NOT NULL, equipe_id INT DEFAULT NULL, INDEX IDX_636F27F66D861B89 (equipe_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE chantier_etape (id INT AUTO_INCREMENT NOT NULL, val_boolean TINYINT DEFAULT NULL, val_integer INT DEFAULT NULL, val_float DOUBLE PRECISION DEFAULT NULL, val_text VARCHAR(255) DEFAULT NULL, val_date DATE DEFAULT NULL, val_date_heure DATETIME DEFAULT NULL, chantier_id INT NOT NULL, etape_id INT NOT NULL, INDEX IDX_3B99027DD0C0049D (chantier_id), INDEX IDX_3B99027D4A8CA2AD (etape_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE chantier_poste (id INT AUTO_INCREMENT NOT NULL, montant_ht DOUBLE PRECISION DEFAULT NULL, montant_ttc DOUBLE PRECISION DEFAULT NULL, montant_fournitures DOUBLE PRECISION DEFAULT NULL, nb_jours_travailles DOUBLE PRECISION DEFAULT NULL, montant_prestataire DOUBLE PRECISION DEFAULT NULL, nom_prestataire VARCHAR(120) DEFAULT NULL, chantier_id INT NOT NULL, poste_id INT NOT NULL, INDEX IDX_6F4F780BD0C0049D (chantier_id), INDEX IDX_6F4F780BA0905086 (poste_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(80) NOT NULL, prenom VARCHAR(80) DEFAULT NULL, telephone VARCHAR(14) DEFAULT NULL, mail VARCHAR(120) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE equipe (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(120) NOT NULL, coefficient DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE etape (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(80) DEFAULT NULL, archive SMALLINT NOT NULL, etape_format_id INT DEFAULT NULL, poste_id INT NOT NULL, INDEX IDX_285F75DDFB3A43EA (etape_format_id), INDEX IDX_285F75DDA0905086 (poste_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE chantier ADD CONSTRAINT FK_636F27F66D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id)');
        $this->addSql('ALTER TABLE chantier_etape ADD CONSTRAINT FK_3B99027DD0C0049D FOREIGN KEY (chantier_id) REFERENCES chantier (id)');
        $this->addSql('ALTER TABLE chantier_etape ADD CONSTRAINT FK_3B99027D4A8CA2AD FOREIGN KEY (etape_id) REFERENCES etape (id)');
        $this->addSql('ALTER TABLE chantier_poste ADD CONSTRAINT FK_6F4F780BD0C0049D FOREIGN KEY (chantier_id) REFERENCES chantier (id)');
        $this->addSql('ALTER TABLE chantier_poste ADD CONSTRAINT FK_6F4F780BA0905086 FOREIGN KEY (poste_id) REFERENCES poste (id)');
        $this->addSql('ALTER TABLE etape ADD CONSTRAINT FK_285F75DDFB3A43EA FOREIGN KEY (etape_format_id) REFERENCES etape_format (id)');
        $this->addSql('ALTER TABLE etape ADD CONSTRAINT FK_285F75DDA0905086 FOREIGN KEY (poste_id) REFERENCES poste (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chantier DROP FOREIGN KEY FK_636F27F66D861B89');
        $this->addSql('ALTER TABLE chantier_etape DROP FOREIGN KEY FK_3B99027DD0C0049D');
        $this->addSql('ALTER TABLE chantier_etape DROP FOREIGN KEY FK_3B99027D4A8CA2AD');
        $this->addSql('ALTER TABLE chantier_poste DROP FOREIGN KEY FK_6F4F780BD0C0049D');
        $this->addSql('ALTER TABLE chantier_poste DROP FOREIGN KEY FK_6F4F780BA0905086');
        $this->addSql('ALTER TABLE etape DROP FOREIGN KEY FK_285F75DDFB3A43EA');
        $this->addSql('ALTER TABLE etape DROP FOREIGN KEY FK_285F75DDA0905086');
        $this->addSql('DROP TABLE chantier');
        $this->addSql('DROP TABLE chantier_etape');
        $this->addSql('DROP TABLE chantier_poste');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE equipe');
        $this->addSql('DROP TABLE etape');
    }
}
