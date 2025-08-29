<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250829113000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create messenger_messages table for Symfony Messenger Doctrine transport (if not exists)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
            CREATE TABLE IF NOT EXISTS messenger_messages (
              id BIGINT AUTO_INCREMENT NOT NULL,
              body LONGTEXT NOT NULL,
              headers LONGTEXT NOT NULL,
              queue_name VARCHAR(190) NOT NULL,
              created_at DATETIME NOT NULL,
              available_at DATETIME NOT NULL,
              delivered_at DATETIME DEFAULT NULL,
              PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ");
        $this->addSql("CREATE INDEX IF NOT EXISTS IDX_MESSENGER_MESSAGES_QUEUE_NAME ON messenger_messages (queue_name)");
        $this->addSql("CREATE INDEX IF NOT EXISTS IDX_MESSENGER_MESSAGES_AVAILABLE_AT ON messenger_messages (available_at)");
        $this->addSql("CREATE INDEX IF NOT EXISTS IDX_MESSENGER_MESSAGES_DELIVERED_AT ON messenger_messages (delivered_at)");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS messenger_messages');
    }
}
