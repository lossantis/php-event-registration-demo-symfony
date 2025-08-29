<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250829150000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add confirmation fields to registration: confirmation_token, confirmation_sent_at, confirmed_at';
    }

    public function up(Schema $schema): void
    {
        // Ensure columns exist (idempotent for MariaDB / MySQL compatible)
        $this->addSql("ALTER TABLE registration ADD COLUMN IF NOT EXISTS confirmation_token VARCHAR(64) DEFAULT NULL");
        $this->addSql("ALTER TABLE registration ADD COLUMN IF NOT EXISTS confirmation_sent_at DATETIME DEFAULT NULL");
        $this->addSql("ALTER TABLE registration ADD COLUMN IF NOT EXISTS confirmed_at DATETIME DEFAULT NULL");
        $this->addSql("CREATE INDEX IF NOT EXISTS IDX_REGISTRATION_CONFIRMATION_TOKEN ON registration (confirmation_token)");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE registration DROP COLUMN IF EXISTS confirmation_token');
        $this->addSql('ALTER TABLE registration DROP COLUMN IF EXISTS confirmation_sent_at');
        $this->addSql('ALTER TABLE registration DROP COLUMN IF EXISTS confirmed_at');
    }
}
