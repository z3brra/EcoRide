<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250930163503 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE driver_review (id INT AUTO_INCREMENT NOT NULL, driver_id INT NOT NULL, author_id INT NOT NULL, drive_id INT NOT NULL, uuid VARCHAR(36) NOT NULL, rate INT NOT NULL, comment LONGTEXT DEFAULT NULL, status VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', UNIQUE INDEX UNIQ_3C1C5F7ED17F50A6 (uuid), INDEX IDX_3C1C5F7EC3423909 (driver_id), INDEX IDX_3C1C5F7EF675F31B (author_id), INDEX IDX_3C1C5F7E86E5E0C4 (drive_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE driver_review ADD CONSTRAINT FK_3C1C5F7EC3423909 FOREIGN KEY (driver_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE driver_review ADD CONSTRAINT FK_3C1C5F7EF675F31B FOREIGN KEY (author_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE driver_review ADD CONSTRAINT FK_3C1C5F7E86E5E0C4 FOREIGN KEY (drive_id) REFERENCES drive (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE driver_review DROP FOREIGN KEY FK_3C1C5F7EC3423909
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE driver_review DROP FOREIGN KEY FK_3C1C5F7EF675F31B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE driver_review DROP FOREIGN KEY FK_3C1C5F7E86E5E0C4
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE driver_review
        SQL);
    }
}
