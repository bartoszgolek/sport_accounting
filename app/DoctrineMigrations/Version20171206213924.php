<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171206213924 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql(
            'CREATE TABLE `booking_book` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `type` int(11) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci'
        );

        $this->addSql(
            'CREATE TABLE `booking_transaction` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `book_id` int(11) DEFAULT NULL,
              `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `credit` decimal(10,2) DEFAULT NULL,
              `debit` decimal(10,2) DEFAULT NULL,
              `voucher` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `date` date DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `IDX_TRANSACTION_BOOK_ID` (`book_id`),
              CONSTRAINT `FK_TRANSACTION_BOOK_ID` FOREIGN KEY (`book_id`) REFERENCES `booking_book` (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci'
        );


        $this->addSql(
            'CREATE TABLE `documents_journal` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `type` int(11) NOT NULL,
              `committed` tinyint(1) NOT NULL,
              `commit_time` datetime DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci'
        );

        $this->addSql(
            'CREATE TABLE `documents_journal_position` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `journal_id` int(11) DEFAULT NULL,
              `book_id` int(11) DEFAULT NULL,
              `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `debit` decimal(10,2) DEFAULT NULL,
              `credit` decimal(10,2) DEFAULT NULL,
              `voucher` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `date` date DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `IDX_POSITION_JOURNAL_ID` (`journal_id`),
              KEY `IDX_POSITION_BOOK_ID` (`book_id`),
              CONSTRAINT `FK_POSITION_BOOK_ID` FOREIGN KEY (`book_id`) REFERENCES `booking_book` (`id`),
              CONSTRAINT `FK_POSITION_JOURNAL_ID` FOREIGN KEY (`journal_id`) REFERENCES `documents_journal` (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci'
        );

        $this->addSql(
            'CREATE TABLE `member` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `book_id` int(11) DEFAULT NULL,
              `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `UNIQ_MEMBER_NAME` (`name`),
              KEY `IDX_MEMBER_BOOK_ID` (`book_id`),
              CONSTRAINT `FK_MEMBER_BOOK_ID` FOREIGN KEY (`book_id`) REFERENCES `booking_book` (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci'
        );

        $this->addSql(
            'CREATE TABLE `fos_user` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `member_id` int(11) DEFAULT NULL,
              `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `username_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `email_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `enabled` tinyint(1) NOT NULL,
              `salt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
              `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `last_login` datetime DEFAULT NULL,
              `locked` tinyint(1) NOT NULL DEFAULT 0,
              `expired` tinyint(1) NOT NULL DEFAULT 0,
              `expires_at` datetime DEFAULT NULL,
              `confirmation_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
              `password_requested_at` datetime DEFAULT NULL,
              `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT \'(DC2Type:array)\',
              `credentials_expired` tinyint(1) NOT NULL DEFAULT 0,
              `credentials_expire_at` datetime DEFAULT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `UNIQ_USER_USERNAME_CANONICAL` (`username_canonical`),
              UNIQUE KEY `UNIQ_USER_EMAIL_CANONICAL` (`email_canonical`),
              KEY `IDX_USER_MEMBER_ID` (`member_id`),
              CONSTRAINT `FK__USER_MEMBER_ID` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci'
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE fos_user');
        $this->addSql('DROP TABLE member');
        $this->addSql('DROP TABLE documents_journal_position');
        $this->addSql('DROP TABLE documents_journal');
        $this->addSql('DROP TABLE booking_transaction');
        $this->addSql('DROP TABLE booking_book');
    }
}
