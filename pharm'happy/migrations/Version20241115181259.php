<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241115181259 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, person_id INT DEFAULT NULL, num VARCHAR(20) NOT NULL, street VARCHAR(200) NOT NULL, city VARCHAR(100) NOT NULL, pc VARCHAR(5) NOT NULL, INDEX IDX_D4E6F81217BBB47 (person_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medical_office (id INT AUTO_INCREMENT NOT NULL, address_id INT NOT NULL, name VARCHAR(100) DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, UNIQUE INDEX UNIQ_1F0FDCADF5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medication (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, price DOUBLE PRECISION NOT NULL, text VARCHAR(1024) DEFAULT NULL, dosage DOUBLE PRECISION NOT NULL, unit VARCHAR(5) NOT NULL, img LONGBLOB DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, person_id INT NOT NULL, address_id INT NOT NULL, delivery_date DATE DEFAULT NULL, order_state VARCHAR(50) NOT NULL, total_price DOUBLE PRECISION DEFAULT NULL, INDEX IDX_F5299398217BBB47 (person_id), INDEX IDX_F5299398F5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, medical_office_id INT DEFAULT NULL, lastname VARCHAR(50) NOT NULL, firstname VARCHAR(50) NOT NULL, birth_dat DATE DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, mail VARCHAR(100) NOT NULL, password VARCHAR(100) NOT NULL, type INT NOT NULL, INDEX IDX_34DCD176AD39C9E (medical_office_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sample (id INT AUTO_INCREMENT NOT NULL, medication_id INT NOT NULL, order_id INT DEFAULT NULL, expiration DATE NOT NULL, INDEX IDX_F10B76C32C4DE6DA (medication_id), INDEX IDX_F10B76C38D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE medical_office ADD CONSTRAINT FK_1F0FDCADF5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176AD39C9E FOREIGN KEY (medical_office_id) REFERENCES medical_office (id)');
        $this->addSql('ALTER TABLE sample ADD CONSTRAINT FK_F10B76C32C4DE6DA FOREIGN KEY (medication_id) REFERENCES medication (id)');
        $this->addSql('ALTER TABLE sample ADD CONSTRAINT FK_F10B76C38D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81217BBB47');
        $this->addSql('ALTER TABLE medical_office DROP FOREIGN KEY FK_1F0FDCADF5B7AF75');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398217BBB47');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398F5B7AF75');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD176AD39C9E');
        $this->addSql('ALTER TABLE sample DROP FOREIGN KEY FK_F10B76C32C4DE6DA');
        $this->addSql('ALTER TABLE sample DROP FOREIGN KEY FK_F10B76C38D9F6D38');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE medical_office');
        $this->addSql('DROP TABLE medication');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE sample');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
