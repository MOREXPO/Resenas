<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231124181011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE medio_categoria (medio_id INT NOT NULL, categoria_id INT NOT NULL, INDEX IDX_39068AA5A40AA46 (medio_id), INDEX IDX_39068AA53397707A (categoria_id), PRIMARY KEY(medio_id, categoria_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE medio_categoria ADD CONSTRAINT FK_39068AA5A40AA46 FOREIGN KEY (medio_id) REFERENCES medio (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE medio_categoria ADD CONSTRAINT FK_39068AA53397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE medio_categoria DROP FOREIGN KEY FK_39068AA5A40AA46');
        $this->addSql('ALTER TABLE medio_categoria DROP FOREIGN KEY FK_39068AA53397707A');
        $this->addSql('DROP TABLE medio_categoria');
    }
}
