<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231124213551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE medio_persona_etiqueta DROP FOREIGN KEY FK_DE0B78C716EC9033');
        $this->addSql('ALTER TABLE medio_persona_etiqueta DROP FOREIGN KEY FK_DE0B78C7D53DA3AB');
        $this->addSql('DROP TABLE medio_persona_etiqueta');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE medio_persona_etiqueta (medio_persona_id INT NOT NULL, etiqueta_id INT NOT NULL, INDEX IDX_DE0B78C7D53DA3AB (etiqueta_id), INDEX IDX_DE0B78C716EC9033 (medio_persona_id), PRIMARY KEY(medio_persona_id, etiqueta_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE medio_persona_etiqueta ADD CONSTRAINT FK_DE0B78C716EC9033 FOREIGN KEY (medio_persona_id) REFERENCES medio_persona (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE medio_persona_etiqueta ADD CONSTRAINT FK_DE0B78C7D53DA3AB FOREIGN KEY (etiqueta_id) REFERENCES etiqueta (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
