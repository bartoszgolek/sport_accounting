<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160420104732 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE player (id INT AUTO_INCREMENT NOT NULL, book_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_98197A655E237E06 (name), INDEX IDX_98197A6516A2B381 (book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A6516A2B381 FOREIGN KEY (book_id) REFERENCES booking_book (id)');
        $this->addSql('ALTER TABLE fos_user DROP FOREIGN KEY FK_957A647916A2B381');
        $this->addSql('DROP INDEX IDX_957A647916A2B381 ON fos_user');
        $this->addSql('ALTER TABLE fos_user CHANGE book_id player_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fos_user ADD CONSTRAINT FK_957A647999E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('CREATE INDEX IDX_957A647999E6F5DF ON fos_user (player_id)');
        $this->addSql('ALTER TABLE documents_journal CHANGE commit_time commit_time DATETIME DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fos_user DROP FOREIGN KEY FK_957A647999E6F5DF');
        $this->addSql('DROP TABLE player');
        $this->addSql('ALTER TABLE documents_journal CHANGE commit_time commit_time DATETIME DEFAULT NULL');
        $this->addSql('DROP INDEX IDX_957A647999E6F5DF ON fos_user');
        $this->addSql('ALTER TABLE fos_user CHANGE player_id book_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fos_user ADD CONSTRAINT FK_957A647916A2B381 FOREIGN KEY (book_id) REFERENCES booking_book (id)');
        $this->addSql('CREATE INDEX IDX_957A647916A2B381 ON fos_user (book_id)');
    }
}
