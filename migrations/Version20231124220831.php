<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231124220831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE elenco (id INT AUTO_INCREMENT NOT NULL, etiqueta_id INT NOT NULL, INDEX IDX_685500B0D53DA3AB (etiqueta_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE elenco_medio (elenco_id INT NOT NULL, medio_id INT NOT NULL, INDEX IDX_A77116EAB81E9E33 (elenco_id), INDEX IDX_A77116EAA40AA46 (medio_id), PRIMARY KEY(elenco_id, medio_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE elenco_persona (elenco_id INT NOT NULL, persona_id INT NOT NULL, INDEX IDX_65A2489B81E9E33 (elenco_id), INDEX IDX_65A2489F5F88DB9 (persona_id), PRIMARY KEY(elenco_id, persona_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE elenco ADD CONSTRAINT FK_685500B0D53DA3AB FOREIGN KEY (etiqueta_id) REFERENCES etiqueta (id)');
        $this->addSql('ALTER TABLE elenco_medio ADD CONSTRAINT FK_A77116EAB81E9E33 FOREIGN KEY (elenco_id) REFERENCES elenco (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE elenco_medio ADD CONSTRAINT FK_A77116EAA40AA46 FOREIGN KEY (medio_id) REFERENCES medio (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE elenco_persona ADD CONSTRAINT FK_65A2489B81E9E33 FOREIGN KEY (elenco_id) REFERENCES elenco (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE elenco_persona ADD CONSTRAINT FK_65A2489F5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE medio_persona_persona DROP FOREIGN KEY FK_6041656316EC9033');
        $this->addSql('ALTER TABLE medio_persona_persona DROP FOREIGN KEY FK_60416563F5F88DB9');
        $this->addSql('ALTER TABLE medio_persona_medio DROP FOREIGN KEY FK_6A41A8FA16EC9033');
        $this->addSql('ALTER TABLE medio_persona_medio DROP FOREIGN KEY FK_6A41A8FAA40AA46');
        $this->addSql('ALTER TABLE medio_persona DROP FOREIGN KEY FK_F8F0EB40D53DA3AB');
        $this->addSql('DROP TABLE medio_persona_persona');
        $this->addSql('DROP TABLE medio_persona_medio');
        $this->addSql('DROP TABLE medio_persona');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE medio_persona_persona (medio_persona_id INT NOT NULL, persona_id INT NOT NULL, INDEX IDX_6041656316EC9033 (medio_persona_id), INDEX IDX_60416563F5F88DB9 (persona_id), PRIMARY KEY(medio_persona_id, persona_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE medio_persona_medio (medio_persona_id INT NOT NULL, medio_id INT NOT NULL, INDEX IDX_6A41A8FAA40AA46 (medio_id), INDEX IDX_6A41A8FA16EC9033 (medio_persona_id), PRIMARY KEY(medio_persona_id, medio_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE medio_persona (id INT AUTO_INCREMENT NOT NULL, etiqueta_id INT NOT NULL, INDEX IDX_F8F0EB40D53DA3AB (etiqueta_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE medio_persona_persona ADD CONSTRAINT FK_6041656316EC9033 FOREIGN KEY (medio_persona_id) REFERENCES medio_persona (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE medio_persona_persona ADD CONSTRAINT FK_60416563F5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE medio_persona_medio ADD CONSTRAINT FK_6A41A8FA16EC9033 FOREIGN KEY (medio_persona_id) REFERENCES medio_persona (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE medio_persona_medio ADD CONSTRAINT FK_6A41A8FAA40AA46 FOREIGN KEY (medio_id) REFERENCES medio (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE medio_persona ADD CONSTRAINT FK_F8F0EB40D53DA3AB FOREIGN KEY (etiqueta_id) REFERENCES etiqueta (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE elenco DROP FOREIGN KEY FK_685500B0D53DA3AB');
        $this->addSql('ALTER TABLE elenco_medio DROP FOREIGN KEY FK_A77116EAB81E9E33');
        $this->addSql('ALTER TABLE elenco_medio DROP FOREIGN KEY FK_A77116EAA40AA46');
        $this->addSql('ALTER TABLE elenco_persona DROP FOREIGN KEY FK_65A2489B81E9E33');
        $this->addSql('ALTER TABLE elenco_persona DROP FOREIGN KEY FK_65A2489F5F88DB9');
        $this->addSql('DROP TABLE elenco');
        $this->addSql('DROP TABLE elenco_medio');
        $this->addSql('DROP TABLE elenco_persona');
    }
}
