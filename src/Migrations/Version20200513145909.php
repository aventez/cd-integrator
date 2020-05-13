<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200513145909 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE offer_offer (offer_source INT NOT NULL, offer_target INT NOT NULL, INDEX IDX_AAFBFAD786DF08D2 (offer_source), INDEX IDX_AAFBFAD79F3A585D (offer_target), PRIMARY KEY(offer_source, offer_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE offer_offer ADD CONSTRAINT FK_AAFBFAD786DF08D2 FOREIGN KEY (offer_source) REFERENCES offer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer_offer ADD CONSTRAINT FK_AAFBFAD79F3A585D FOREIGN KEY (offer_target) REFERENCES offer (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE offer_offer');
    }
}
