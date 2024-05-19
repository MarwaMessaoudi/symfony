<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240518165344 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payement CHANGE payement_flight_id payement_flight_id INT DEFAULT NULL, CHANGE payement_hotel_id payement_hotel_id INT DEFAULT NULL, CHANGE peyement_tour_id peyement_tour_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE payement CHANGE payement_flight_id payement_flight_id INT NOT NULL, CHANGE payement_hotel_id payement_hotel_id INT NOT NULL, CHANGE peyement_tour_id peyement_tour_id INT NOT NULL');
    }
}
