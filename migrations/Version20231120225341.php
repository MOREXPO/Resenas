<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231120225341 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categoria (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etiqueta (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inteligencia_artificial (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medio (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, ano_lanzamiento INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medio_persona (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medio_persona_medio (medio_persona_id INT NOT NULL, medio_id INT NOT NULL, INDEX IDX_6A41A8FA16EC9033 (medio_persona_id), INDEX IDX_6A41A8FAA40AA46 (medio_id), PRIMARY KEY(medio_persona_id, medio_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medio_persona_persona (medio_persona_id INT NOT NULL, persona_id INT NOT NULL, INDEX IDX_6041656316EC9033 (medio_persona_id), INDEX IDX_60416563F5F88DB9 (persona_id), PRIMARY KEY(medio_persona_id, persona_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medio_persona_etiqueta (medio_persona_id INT NOT NULL, etiqueta_id INT NOT NULL, INDEX IDX_DE0B78C716EC9033 (medio_persona_id), INDEX IDX_DE0B78C7D53DA3AB (etiqueta_id), PRIMARY KEY(medio_persona_id, etiqueta_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pagina (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, dominio VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pagina_categoria (pagina_id INT NOT NULL, categoria_id INT NOT NULL, INDEX IDX_1851BEB057991ECF (pagina_id), INDEX IDX_1851BEB03397707A (categoria_id), PRIMARY KEY(pagina_id, categoria_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE persona (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, nacionalidad VARCHAR(255) NOT NULL, fecha_nacimiento DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE persona_etiqueta (persona_id INT NOT NULL, etiqueta_id INT NOT NULL, INDEX IDX_8D8FB27BF5F88DB9 (persona_id), INDEX IDX_8D8FB27BD53DA3AB (etiqueta_id), PRIMARY KEY(persona_id, etiqueta_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resena (id INT AUTO_INCREMENT NOT NULL, medio_id INT DEFAULT NULL, pagina_id INT NOT NULL, autor VARCHAR(255) NOT NULL, texto LONGTEXT DEFAULT NULL, INDEX IDX_50A7E40AA40AA46 (medio_id), INDEX IDX_50A7E40A57991ECF (pagina_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE valoracion (id INT AUTO_INCREMENT NOT NULL, resena_id INT NOT NULL, inteligencia_artificial_id INT NOT NULL, calificacion DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_6D3DE0F419764015 (resena_id), INDEX IDX_6D3DE0F45C293311 (inteligencia_artificial_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE medio_persona_medio ADD CONSTRAINT FK_6A41A8FA16EC9033 FOREIGN KEY (medio_persona_id) REFERENCES medio_persona (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE medio_persona_medio ADD CONSTRAINT FK_6A41A8FAA40AA46 FOREIGN KEY (medio_id) REFERENCES medio (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE medio_persona_persona ADD CONSTRAINT FK_6041656316EC9033 FOREIGN KEY (medio_persona_id) REFERENCES medio_persona (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE medio_persona_persona ADD CONSTRAINT FK_60416563F5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE medio_persona_etiqueta ADD CONSTRAINT FK_DE0B78C716EC9033 FOREIGN KEY (medio_persona_id) REFERENCES medio_persona (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE medio_persona_etiqueta ADD CONSTRAINT FK_DE0B78C7D53DA3AB FOREIGN KEY (etiqueta_id) REFERENCES etiqueta (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pagina_categoria ADD CONSTRAINT FK_1851BEB057991ECF FOREIGN KEY (pagina_id) REFERENCES pagina (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pagina_categoria ADD CONSTRAINT FK_1851BEB03397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE persona_etiqueta ADD CONSTRAINT FK_8D8FB27BF5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE persona_etiqueta ADD CONSTRAINT FK_8D8FB27BD53DA3AB FOREIGN KEY (etiqueta_id) REFERENCES etiqueta (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE resena ADD CONSTRAINT FK_50A7E40AA40AA46 FOREIGN KEY (medio_id) REFERENCES medio (id)');
        $this->addSql('ALTER TABLE resena ADD CONSTRAINT FK_50A7E40A57991ECF FOREIGN KEY (pagina_id) REFERENCES pagina (id)');
        $this->addSql('ALTER TABLE valoracion ADD CONSTRAINT FK_6D3DE0F419764015 FOREIGN KEY (resena_id) REFERENCES resena (id)');
        $this->addSql('ALTER TABLE valoracion ADD CONSTRAINT FK_6D3DE0F45C293311 FOREIGN KEY (inteligencia_artificial_id) REFERENCES inteligencia_artificial (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE medio_persona_medio DROP FOREIGN KEY FK_6A41A8FA16EC9033');
        $this->addSql('ALTER TABLE medio_persona_medio DROP FOREIGN KEY FK_6A41A8FAA40AA46');
        $this->addSql('ALTER TABLE medio_persona_persona DROP FOREIGN KEY FK_6041656316EC9033');
        $this->addSql('ALTER TABLE medio_persona_persona DROP FOREIGN KEY FK_60416563F5F88DB9');
        $this->addSql('ALTER TABLE medio_persona_etiqueta DROP FOREIGN KEY FK_DE0B78C716EC9033');
        $this->addSql('ALTER TABLE medio_persona_etiqueta DROP FOREIGN KEY FK_DE0B78C7D53DA3AB');
        $this->addSql('ALTER TABLE pagina_categoria DROP FOREIGN KEY FK_1851BEB057991ECF');
        $this->addSql('ALTER TABLE pagina_categoria DROP FOREIGN KEY FK_1851BEB03397707A');
        $this->addSql('ALTER TABLE persona_etiqueta DROP FOREIGN KEY FK_8D8FB27BF5F88DB9');
        $this->addSql('ALTER TABLE persona_etiqueta DROP FOREIGN KEY FK_8D8FB27BD53DA3AB');
        $this->addSql('ALTER TABLE resena DROP FOREIGN KEY FK_50A7E40AA40AA46');
        $this->addSql('ALTER TABLE resena DROP FOREIGN KEY FK_50A7E40A57991ECF');
        $this->addSql('ALTER TABLE valoracion DROP FOREIGN KEY FK_6D3DE0F419764015');
        $this->addSql('ALTER TABLE valoracion DROP FOREIGN KEY FK_6D3DE0F45C293311');
        $this->addSql('DROP TABLE categoria');
        $this->addSql('DROP TABLE etiqueta');
        $this->addSql('DROP TABLE inteligencia_artificial');
        $this->addSql('DROP TABLE medio');
        $this->addSql('DROP TABLE medio_persona');
        $this->addSql('DROP TABLE medio_persona_medio');
        $this->addSql('DROP TABLE medio_persona_persona');
        $this->addSql('DROP TABLE medio_persona_etiqueta');
        $this->addSql('DROP TABLE pagina');
        $this->addSql('DROP TABLE pagina_categoria');
        $this->addSql('DROP TABLE persona');
        $this->addSql('DROP TABLE persona_etiqueta');
        $this->addSql('DROP TABLE resena');
        $this->addSql('DROP TABLE valoracion');
    }
}
