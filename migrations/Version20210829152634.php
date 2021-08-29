<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210829152634 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event CHANGE is_active is_active TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE report ADD event_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F778471F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C42F778471F7E88B ON report (event_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event CHANGE is_active is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F778471F7E88B');
        $this->addSql('DROP INDEX UNIQ_C42F778471F7E88B ON report');
        $this->addSql('ALTER TABLE report DROP event_id');
    }
}
