<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160413231126 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE documents_journal_position (id INT AUTO_INCREMENT NOT NULL, journal_id INT NOT NULL, description VARCHAR(255) NOT NULL, debit NUMERIC(10, 2) DEFAULT NULL, credit NUMERIC(10, 2) DEFAULT NULL, voucher VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE documents_journal (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, type INT NOT NULL, commited TINYINT(1) NOT NULL, commit_time DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fos_user (id INT AUTO_INCREMENT NOT NULL, book_id INT DEFAULT NULL, username VARCHAR(255) NOT NULL, username_canonical VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_canonical VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, locked TINYINT(1) NOT NULL, expired TINYINT(1) NOT NULL, expires_at DATETIME DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', credentials_expired TINYINT(1) NOT NULL, credentials_expire_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_957A647992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_957A6479A0D96FBF (email_canonical), INDEX IDX_957A647916A2B381 (book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE booking_book (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, type INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE booking_transaction (id INT AUTO_INCREMENT NOT NULL, book_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, credit NUMERIC(10, 2) DEFAULT NULL, debit NUMERIC(10, 2) DEFAULT NULL, INDEX IDX_55ACDE1816A2B381 (book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fos_user ADD CONSTRAINT FK_957A647916A2B381 FOREIGN KEY (book_id) REFERENCES booking_book (id)');
        $this->addSql('ALTER TABLE booking_transaction ADD CONSTRAINT FK_55ACDE1816A2B381 FOREIGN KEY (book_id) REFERENCES booking_book (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fos_user DROP FOREIGN KEY FK_957A647916A2B381');
        $this->addSql('ALTER TABLE booking_transaction DROP FOREIGN KEY FK_55ACDE1816A2B381');
        $this->addSql('DROP TABLE documents_journal_position');
        $this->addSql('DROP TABLE documents_journal');
        $this->addSql('DROP TABLE fos_user');
        $this->addSql('DROP TABLE booking_book');
        $this->addSql('DROP TABLE booking_transaction');
    }
}
