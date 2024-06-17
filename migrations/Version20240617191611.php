<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240617191611 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE elenco CHANGE audiovisual_id audiovisual_id INT NOT NULL, CHANGE persona_id persona_id INT NOT NULL, CHANGE etiqueta_id etiqueta_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE elenco CHANGE audiovisual_id audiovisual_id INT DEFAULT NULL, CHANGE persona_id persona_id INT DEFAULT NULL, CHANGE etiqueta_id etiqueta_id INT DEFAULT NULL');
    }
}
