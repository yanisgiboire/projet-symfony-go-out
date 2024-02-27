<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240227131524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE go_out DROP FOREIGN KEY FK_6A94D5A29D1C3019');
        $this->addSql('DROP INDEX IDX_6A94D5A29D1C3019 ON go_out');
        $this->addSql('ALTER TABLE go_out CHANGE participant_id organizer_id INT NOT NULL');
        $this->addSql('ALTER TABLE go_out ADD CONSTRAINT FK_6A94D5A2876C4DDA FOREIGN KEY (organizer_id) REFERENCES participant (id)');
        $this->addSql('CREATE INDEX IDX_6A94D5A2876C4DDA ON go_out (organizer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE go_out DROP FOREIGN KEY FK_6A94D5A2876C4DDA');
        $this->addSql('DROP INDEX IDX_6A94D5A2876C4DDA ON go_out');
        $this->addSql('ALTER TABLE go_out CHANGE organizer_id participant_id INT NOT NULL');
        $this->addSql('ALTER TABLE go_out ADD CONSTRAINT FK_6A94D5A29D1C3019 FOREIGN KEY (participant_id) REFERENCES participant (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_6A94D5A29D1C3019 ON go_out (participant_id)');
    }
}
