<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160414134743 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE documents_journal CHANGE commit_time commit_time DATETIME NOT NULL');
        $this->addSql('ALTER TABLE documents_journal_position ADD book_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE documents_journal_position ADD CONSTRAINT FK_165E2C0916A2B381 FOREIGN KEY (book_id) REFERENCES booking_book (id)');
        $this->addSql('CREATE INDEX IDX_165E2C0916A2B381 ON documents_journal_position (book_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE documents_journal CHANGE commit_time commit_time DATETIME NOT NULL');
        $this->addSql('ALTER TABLE documents_journal_position DROP FOREIGN KEY FK_165E2C0916A2B381');
        $this->addSql('DROP INDEX IDX_165E2C0916A2B381 ON documents_journal_position');
        $this->addSql('ALTER TABLE documents_journal_position DROP book_id');
    }
}
