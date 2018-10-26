<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181026113552 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE type (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, data CLOB NOT NULL, updated_at INTEGER NOT NULL, created_at INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE item (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, active BOOLEAN NOT NULL, updated_at INTEGER NOT NULL, created_at INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_1F1B251EC54C8C93 ON item (type_id)');
        $this->addSql('CREATE TABLE item_tag (item_id INTEGER NOT NULL, tag_id INTEGER NOT NULL, PRIMARY KEY(item_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_E49CCCB1126F525E ON item_tag (item_id)');
        $this->addSql('CREATE INDEX IDX_E49CCCB1BAD26311 ON item_tag (tag_id)');
        $this->addSql('CREATE TABLE item_content (item_id INTEGER NOT NULL, content_id INTEGER NOT NULL, PRIMARY KEY(item_id, content_id))');
        $this->addSql('CREATE INDEX IDX_F59816FD126F525E ON item_content (item_id)');
        $this->addSql('CREATE INDEX IDX_F59816FD84A0A3ED ON item_content (content_id)');
        $this->addSql('CREATE TABLE content (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type_id INTEGER NOT NULL, data CLOB NOT NULL, updated_at INTEGER NOT NULL, created_at INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_FEC530A9C54C8C93 ON content (type_id)');
        $this->addSql('CREATE TABLE tag (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type_id INTEGER NOT NULL, data CLOB NOT NULL, updated_at INTEGER NOT NULL, created_at INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_389B783C54C8C93 ON tag (type_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE item_tag');
        $this->addSql('DROP TABLE item_content');
        $this->addSql('DROP TABLE content');
        $this->addSql('DROP TABLE tag');
    }
}
