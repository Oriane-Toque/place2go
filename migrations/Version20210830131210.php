<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210830131210 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attendant (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, event_id INT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_B2508F91A76ED395 (user_id), INDEX IDX_B2508F9171F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, picture VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, event_id INT NOT NULL, content VARCHAR(500) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_9474526CF675F31B (author_id), INDEX IDX_9474526C71F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, title VARCHAR(50) NOT NULL, description LONGTEXT NOT NULL, event_date DATETIME NOT NULL, address VARCHAR(255) DEFAULT NULL, lat VARCHAR(255) DEFAULT NULL, lon VARCHAR(255) DEFAULT NULL, max_attendants SMALLINT NOT NULL, is_active TINYINT(1) DEFAULT \'1\' NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_3BAE0AA7F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_category (event_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_40A0F01171F7E88B (event_id), INDEX IDX_40A0F01112469DE2 (category_id), PRIMARY KEY(event_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE friendship (id INT AUTO_INCREMENT NOT NULL, sender_id INT NOT NULL, receiver_id INT NOT NULL, status SMALLINT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7234A45FF624B39D (sender_id), INDEX IDX_7234A45FCD53EDB6 (receiver_id), UNIQUE INDEX friendship_unique (sender_id, receiver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, event_id INT DEFAULT NULL, author_id INT NOT NULL, type VARCHAR(255) NOT NULL, message VARCHAR(255) NOT NULL, status TINYINT(1) DEFAULT \'0\' NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_C42F7784A76ED395 (user_id), UNIQUE INDEX UNIQ_C42F778471F7E88B (event_id), INDEX IDX_C42F7784F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nickname VARCHAR(50) NOT NULL, firstname VARCHAR(50) NOT NULL, lastname VARCHAR(50) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, city VARCHAR(50) NOT NULL, phone VARCHAR(20) DEFAULT NULL, description LONGTEXT DEFAULT NULL, birthday DATE NOT NULL, is_active TINYINT(1) NOT NULL, is_verified TINYINT(1) NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_8D93D649A188FE64 (nickname), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attendant ADD CONSTRAINT FK_B2508F91A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE attendant ADD CONSTRAINT FK_B2508F9171F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE event_category ADD CONSTRAINT FK_40A0F01171F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_category ADD CONSTRAINT FK_40A0F01112469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE friendship ADD CONSTRAINT FK_7234A45FF624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE friendship ADD CONSTRAINT FK_7234A45FCD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F778471F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_category DROP FOREIGN KEY FK_40A0F01112469DE2');
        $this->addSql('ALTER TABLE attendant DROP FOREIGN KEY FK_B2508F9171F7E88B');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C71F7E88B');
        $this->addSql('ALTER TABLE event_category DROP FOREIGN KEY FK_40A0F01171F7E88B');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F778471F7E88B');
        $this->addSql('ALTER TABLE attendant DROP FOREIGN KEY FK_B2508F91A76ED395');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CF675F31B');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7F675F31B');
        $this->addSql('ALTER TABLE friendship DROP FOREIGN KEY FK_7234A45FF624B39D');
        $this->addSql('ALTER TABLE friendship DROP FOREIGN KEY FK_7234A45FCD53EDB6');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784A76ED395');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784F675F31B');
        $this->addSql('DROP TABLE attendant');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_category');
        $this->addSql('DROP TABLE friendship');
        $this->addSql('DROP TABLE report');
        $this->addSql('DROP TABLE user');
    }
}
