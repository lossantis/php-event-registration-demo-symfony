<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250829141300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create registration table with required fields and constraints (MariaDB conventions)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
            CREATE TABLE IF NOT EXISTS registration (
              id INT AUTO_INCREMENT NOT NULL,
              name VARCHAR(255) NOT NULL,
              plus_one TINYINT(1) NOT NULL,
              number_of_kids INT UNSIGNED NOT NULL DEFAULT 0,
              number_of_vegetarians INT UNSIGNED NOT NULL DEFAULT 0,
              email VARCHAR(255) NOT NULL,
              department VARCHAR(63) NOT NULL,
              UNIQUE INDEX UNIQ_REGISTRATION_EMAIL (email),
              PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS registration');
    }
}
