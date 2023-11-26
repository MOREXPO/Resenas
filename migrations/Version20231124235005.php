<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231124235005 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE elenco ADD medio_id INT NOT NULL');
        $this->addSql('ALTER TABLE elenco ADD CONSTRAINT FK_685500B0A40AA46 FOREIGN KEY (medio_id) REFERENCES medio (id)');
        $this->addSql('CREATE INDEX IDX_685500B0A40AA46 ON elenco (medio_id)');
        $this->addSql('ALTER TABLE medio DROP FOREIGN KEY FK_8D948C0BB81E9E33');
        $this->addSql('DROP INDEX IDX_8D948C0BB81E9E33 ON medio');
        $this->addSql('ALTER TABLE medio DROP elenco_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE medio ADD elenco_id INT NOT NULL');
        $this->addSql('ALTER TABLE medio ADD CONSTRAINT FK_8D948C0BB81E9E33 FOREIGN KEY (elenco_id) REFERENCES elenco (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_8D948C0BB81E9E33 ON medio (elenco_id)');
        $this->addSql('ALTER TABLE elenco DROP FOREIGN KEY FK_685500B0A40AA46');
        $this->addSql('DROP INDEX IDX_685500B0A40AA46 ON elenco');
        $this->addSql('ALTER TABLE elenco DROP medio_id');
    }
}
