<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250622173157 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE custom_driver_preference (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, uuid VARCHAR(36) NOT NULL, label VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', UNIQUE INDEX UNIQ_CB625E15D17F50A6 (uuid), INDEX IDX_CB625E157E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE drive (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, vehicle_id INT NOT NULL, uuid VARCHAR(36) NOT NULL, reference VARCHAR(20) NOT NULL, status VARCHAR(255) NOT NULL, available_seats INT NOT NULL, price INT NOT NULL, distance DOUBLE PRECISION NOT NULL, depart VARCHAR(255) NOT NULL, depart_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', arrived VARCHAR(255) NOT NULL, arrived_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', UNIQUE INDEX UNIQ_681DF58FD17F50A6 (uuid), UNIQUE INDEX UNIQ_681DF58FAEA34913 (reference), INDEX IDX_681DF58F7E3C61F9 (owner_id), INDEX IDX_681DF58F545317D1 (vehicle_id), INDEX IDX_681DF58FAEA34913 (reference), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE drive_user (drive_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_EB62B60886E5E0C4 (drive_id), INDEX IDX_EB62B608A76ED395 (user_id), PRIMARY KEY(drive_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE fixed_driver_preference (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, animals TINYINT(1) DEFAULT 0 NOT NULL, smoke TINYINT(1) DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', UNIQUE INDEX UNIQ_6D3937087E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, uuid VARCHAR(36) NOT NULL, email VARCHAR(180) NOT NULL, pseudo VARCHAR(64) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, credits INT DEFAULT NULL, is_banned TINYINT(1) NOT NULL, api_token VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', deleted_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', UNIQUE INDEX UNIQ_8D93D649D17F50A6 (uuid), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE vehicle (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, uuid VARCHAR(36) NOT NULL, license_plate VARCHAR(20) NOT NULL, first_license_date DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', is_electric TINYINT(1) NOT NULL, color VARCHAR(20) NOT NULL, seats INT NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', UNIQUE INDEX UNIQ_1B80E486D17F50A6 (uuid), UNIQUE INDEX UNIQ_1B80E486F5AA79D0 (license_plate), INDEX IDX_1B80E4867E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE custom_driver_preference ADD CONSTRAINT FK_CB625E157E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE drive ADD CONSTRAINT FK_681DF58F7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE drive ADD CONSTRAINT FK_681DF58F545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE drive_user ADD CONSTRAINT FK_EB62B60886E5E0C4 FOREIGN KEY (drive_id) REFERENCES drive (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE drive_user ADD CONSTRAINT FK_EB62B608A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE fixed_driver_preference ADD CONSTRAINT FK_6D3937087E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E4867E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE custom_driver_preference DROP FOREIGN KEY FK_CB625E157E3C61F9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE drive DROP FOREIGN KEY FK_681DF58F7E3C61F9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE drive DROP FOREIGN KEY FK_681DF58F545317D1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE drive_user DROP FOREIGN KEY FK_EB62B60886E5E0C4
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE drive_user DROP FOREIGN KEY FK_EB62B608A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE fixed_driver_preference DROP FOREIGN KEY FK_6D3937087E3C61F9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E4867E3C61F9
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE custom_driver_preference
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE drive
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE drive_user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE fixed_driver_preference
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE vehicle
        SQL);
    }
}
