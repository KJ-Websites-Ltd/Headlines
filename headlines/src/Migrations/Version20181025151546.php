<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181025151546 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE item_tag (item_id INTEGER NOT NULL, tag_id INTEGER NOT NULL, PRIMARY KEY(item_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_E49CCCB1126F525E ON item_tag (item_id)');
        $this->addSql('CREATE INDEX IDX_E49CCCB1BAD26311 ON item_tag (tag_id)');
        $this->addSql('CREATE TABLE item_type (item_id INTEGER NOT NULL, type_id INTEGER NOT NULL, PRIMARY KEY(item_id, type_id))');
        $this->addSql('CREATE INDEX IDX_44EE13D2126F525E ON item_type (item_id)');
        $this->addSql('CREATE INDEX IDX_44EE13D2C54C8C93 ON item_type (type_id)');
        $this->addSql('DROP INDEX IDX_F59816FD84A0A3ED');
        $this->addSql('DROP INDEX IDX_F59816FD126F525E');
        $this->addSql('CREATE TEMPORARY TABLE __temp__item_content AS SELECT item_id, content_id FROM item_content');
        $this->addSql('DROP TABLE item_content');
        $this->addSql('CREATE TABLE item_content (item_id INTEGER NOT NULL, content_id INTEGER NOT NULL, PRIMARY KEY(item_id, content_id), CONSTRAINT FK_F59816FD126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_F59816FD84A0A3ED FOREIGN KEY (content_id) REFERENCES content (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO item_content (item_id, content_id) SELECT item_id, content_id FROM __temp__item_content');
        $this->addSql('DROP TABLE __temp__item_content');
        $this->addSql('CREATE INDEX IDX_F59816FD84A0A3ED ON item_content (content_id)');
        $this->addSql('CREATE INDEX IDX_F59816FD126F525E ON item_content (item_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE item_tag');
        $this->addSql('DROP TABLE item_type');
        $this->addSql('DROP INDEX IDX_F59816FD126F525E');
        $this->addSql('DROP INDEX IDX_F59816FD84A0A3ED');
        $this->addSql('CREATE TEMPORARY TABLE __temp__item_content AS SELECT item_id, content_id FROM item_content');
        $this->addSql('DROP TABLE item_content');
        $this->addSql('CREATE TABLE item_content (item_id INTEGER NOT NULL, content_id INTEGER NOT NULL, PRIMARY KEY(item_id, content_id))');
        $this->addSql('INSERT INTO item_content (item_id, content_id) SELECT item_id, content_id FROM __temp__item_content');
        $this->addSql('DROP TABLE __temp__item_content');
        $this->addSql('CREATE INDEX IDX_F59816FD126F525E ON item_content (item_id)');
        $this->addSql('CREATE INDEX IDX_F59816FD84A0A3ED ON item_content (content_id)');
    }
}
