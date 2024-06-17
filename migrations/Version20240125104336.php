<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240125104336 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE medio (id INT AUTO_INCREMENT NOT NULL, tipo INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medio_categoria (medio_id INT NOT NULL, categoria_id INT NOT NULL, INDEX IDX_39068AA5A40AA46 (medio_id), INDEX IDX_39068AA53397707A (categoria_id), PRIMARY KEY(medio_id, categoria_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE medio_categoria ADD CONSTRAINT FK_39068AA5A40AA46 FOREIGN KEY (medio_id) REFERENCES medio (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE medio_categoria ADD CONSTRAINT FK_39068AA53397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE audiovisual_categoria DROP FOREIGN KEY FK_F1A5A8053397707A');
        $this->addSql('ALTER TABLE audiovisual_categoria DROP FOREIGN KEY FK_F1A5A805C1432557');
        $this->addSql('DROP TABLE audiovisual_categoria');
        $this->addSql('ALTER TABLE audiovisual ADD medio_id INT NOT NULL');
        $this->addSql('ALTER TABLE audiovisual ADD CONSTRAINT FK_363C840A40AA46 FOREIGN KEY (medio_id) REFERENCES medio (id)');
        $this->addSql('CREATE INDEX IDX_363C840A40AA46 ON audiovisual (medio_id)');
        $this->addSql('ALTER TABLE resena DROP FOREIGN KEY FK_50A7E40AC1432557');
        $this->addSql('DROP INDEX IDX_50A7E40AC1432557 ON resena');
        $this->addSql('ALTER TABLE resena CHANGE audiovisual_id medio_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE resena ADD CONSTRAINT FK_50A7E40AA40AA46 FOREIGN KEY (medio_id) REFERENCES medio (id)');
        $this->addSql('CREATE INDEX IDX_50A7E40AA40AA46 ON resena (medio_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE audiovisual DROP FOREIGN KEY FK_363C840A40AA46');
        $this->addSql('ALTER TABLE resena DROP FOREIGN KEY FK_50A7E40AA40AA46');
        $this->addSql('CREATE TABLE audiovisual_categoria (audiovisual_id INT NOT NULL, categoria_id INT NOT NULL, INDEX IDX_F1A5A8053397707A (categoria_id), INDEX IDX_F1A5A805C1432557 (audiovisual_id), PRIMARY KEY(audiovisual_id, categoria_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE audiovisual_categoria ADD CONSTRAINT FK_F1A5A8053397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE audiovisual_categoria ADD CONSTRAINT FK_F1A5A805C1432557 FOREIGN KEY (audiovisual_id) REFERENCES audiovisual (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE medio_categoria DROP FOREIGN KEY FK_39068AA5A40AA46');
        $this->addSql('ALTER TABLE medio_categoria DROP FOREIGN KEY FK_39068AA53397707A');
        $this->addSql('DROP TABLE medio');
        $this->addSql('DROP TABLE medio_categoria');
        $this->addSql('DROP INDEX IDX_363C840A40AA46 ON audiovisual');
        $this->addSql('ALTER TABLE audiovisual DROP medio_id');
        $this->addSql('DROP INDEX IDX_50A7E40AA40AA46 ON resena');
        $this->addSql('ALTER TABLE resena CHANGE medio_id audiovisual_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE resena ADD CONSTRAINT FK_50A7E40AC1432557 FOREIGN KEY (audiovisual_id) REFERENCES audiovisual (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_50A7E40AC1432557 ON resena (audiovisual_id)');
    }
}
