<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231124225758 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE elenco_medio DROP FOREIGN KEY FK_A77116EAA40AA46');
        $this->addSql('ALTER TABLE elenco_medio DROP FOREIGN KEY FK_A77116EAB81E9E33');
        $this->addSql('DROP TABLE elenco_medio');
        $this->addSql('ALTER TABLE medio ADD elenco_id INT NOT NULL');
        $this->addSql('ALTER TABLE medio ADD CONSTRAINT FK_8D948C0BB81E9E33 FOREIGN KEY (elenco_id) REFERENCES elenco (id)');
        $this->addSql('CREATE INDEX IDX_8D948C0BB81E9E33 ON medio (elenco_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE elenco_medio (elenco_id INT NOT NULL, medio_id INT NOT NULL, INDEX IDX_A77116EAA40AA46 (medio_id), INDEX IDX_A77116EAB81E9E33 (elenco_id), PRIMARY KEY(elenco_id, medio_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE elenco_medio ADD CONSTRAINT FK_A77116EAA40AA46 FOREIGN KEY (medio_id) REFERENCES medio (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE elenco_medio ADD CONSTRAINT FK_A77116EAB81E9E33 FOREIGN KEY (elenco_id) REFERENCES elenco (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE medio DROP FOREIGN KEY FK_8D948C0BB81E9E33');
        $this->addSql('DROP INDEX IDX_8D948C0BB81E9E33 ON medio');
        $this->addSql('ALTER TABLE medio DROP elenco_id');
    }
}
