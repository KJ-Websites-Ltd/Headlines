<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181025144635 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE content (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, data CLOB NOT NULL, tag_id INTEGER NOT NULL, created_at INTEGER NOT NULL, updated_at INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE tag (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, data CLOB NOT NULL, created_at INTEGER NOT NULL, updated_at INTEGER NOT NULL)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__page AS SELECT id, title, slug, active, created_at, updated_at FROM page');
        $this->addSql('DROP TABLE page');
        $this->addSql('CREATE TABLE page (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL COLLATE BINARY, slug VARCHAR(255) NOT NULL COLLATE BINARY, active INTEGER NOT NULL, created_at INTEGER NOT NULL, updated_at INTEGER NOT NULL)');
        $this->addSql('INSERT INTO page (id, title, slug, active, created_at, updated_at) SELECT id, title, slug, active, created_at, updated_at FROM __temp__page');
        $this->addSql('DROP TABLE __temp__page');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE content');
        $this->addSql('DROP TABLE tag');
        $this->addSql('CREATE TEMPORARY TABLE __temp__page AS SELECT id, title, slug, active, created_at, updated_at FROM page');
        $this->addSql('DROP TABLE page');
        $this->addSql('CREATE TABLE page (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, active INTEGER NOT NULL, created_at INTEGER DEFAULT NULL, updated_at INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO page (id, title, slug, active, created_at, updated_at) SELECT id, title, slug, active, created_at, updated_at FROM __temp__page');
        $this->addSql('DROP TABLE __temp__page');
    }
}
