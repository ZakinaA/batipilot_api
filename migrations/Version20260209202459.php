<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260209202459 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chantier ADD client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE chantier ADD CONSTRAINT FK_636F27F66D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id)');
        $this->addSql('ALTER TABLE chantier ADD CONSTRAINT FK_636F27F619EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_636F27F619EB6921 ON chantier (client_id)');
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
        $this->addSql('ALTER TABLE chantier DROP FOREIGN KEY FK_636F27F619EB6921');
        $this->addSql('DROP INDEX IDX_636F27F619EB6921 ON chantier');
        $this->addSql('ALTER TABLE chantier DROP client_id');
        $this->addSql('ALTER TABLE chantier_etape DROP FOREIGN KEY FK_3B99027DD0C0049D');
        $this->addSql('ALTER TABLE chantier_etape DROP FOREIGN KEY FK_3B99027D4A8CA2AD');
        $this->addSql('ALTER TABLE chantier_poste DROP FOREIGN KEY FK_6F4F780BD0C0049D');
        $this->addSql('ALTER TABLE chantier_poste DROP FOREIGN KEY FK_6F4F780BA0905086');
        $this->addSql('ALTER TABLE etape DROP FOREIGN KEY FK_285F75DDFB3A43EA');
        $this->addSql('ALTER TABLE etape DROP FOREIGN KEY FK_285F75DDA0905086');
    }
}
