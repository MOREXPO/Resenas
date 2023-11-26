<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231124215007 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE medio_persona ADD etiqueta_id INT NOT NULL');
        $this->addSql('ALTER TABLE medio_persona ADD CONSTRAINT FK_F8F0EB40D53DA3AB FOREIGN KEY (etiqueta_id) REFERENCES etiqueta (id)');
        $this->addSql('CREATE INDEX IDX_F8F0EB40D53DA3AB ON medio_persona (etiqueta_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE medio_persona DROP FOREIGN KEY FK_F8F0EB40D53DA3AB');
        $this->addSql('DROP INDEX IDX_F8F0EB40D53DA3AB ON medio_persona');
        $this->addSql('ALTER TABLE medio_persona DROP etiqueta_id');
    }
}
