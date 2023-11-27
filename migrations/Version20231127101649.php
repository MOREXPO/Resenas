<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231127101649 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE medio_persona_etiqueta MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON medio_persona_etiqueta');
        $this->addSql('ALTER TABLE medio_persona_etiqueta DROP id');
        $this->addSql('ALTER TABLE medio_persona_etiqueta ADD PRIMARY KEY (medio_id, persona_id, etiqueta_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE medio_persona_etiqueta ADD id INT AUTO_INCREMENT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
    }
}
