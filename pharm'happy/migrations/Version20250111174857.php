<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250111174857 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE medical_office DROP FOREIGN KEY FK_1F0FDCADF5B7AF75');
        $this->addSql('DROP INDEX UNIQ_1F0FDCADF5B7AF75 ON medical_office');
        $this->addSql('ALTER TABLE medical_office DROP address_id');
        $this->addSql('ALTER TABLE medication CHANGE pharmacy_only pharmacy_only TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE medication CHANGE pharmacy_only pharmacy_only TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE medical_office ADD address_id INT NOT NULL');
        $this->addSql('ALTER TABLE medical_office ADD CONSTRAINT FK_1F0FDCADF5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1F0FDCADF5B7AF75 ON medical_office (address_id)');
    }
}
