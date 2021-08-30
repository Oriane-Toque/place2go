<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210817091802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event_category (event_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_40A0F01171F7E88B (event_id), INDEX IDX_40A0F01112469DE2 (category_id), PRIMARY KEY(event_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_category ADD CONSTRAINT FK_40A0F01171F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_category ADD CONSTRAINT FK_40A0F01112469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE attendant ADD user_id INT NOT NULL, ADD event_id INT NOT NULL');
        $this->addSql('ALTER TABLE attendant ADD CONSTRAINT FK_B2508F91A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE attendant ADD CONSTRAINT FK_B2508F9171F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('CREATE INDEX IDX_B2508F91A76ED395 ON attendant (user_id)');
        $this->addSql('CREATE INDEX IDX_B2508F9171F7E88B ON attendant (event_id)');
        $this->addSql('ALTER TABLE event ADD author_id INT NOT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7F675F31B ON event (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE event_category');
        $this->addSql('ALTER TABLE attendant DROP FOREIGN KEY FK_B2508F91A76ED395');
        $this->addSql('ALTER TABLE attendant DROP FOREIGN KEY FK_B2508F9171F7E88B');
        $this->addSql('DROP INDEX IDX_B2508F91A76ED395 ON attendant');
        $this->addSql('DROP INDEX IDX_B2508F9171F7E88B ON attendant');
        $this->addSql('ALTER TABLE attendant DROP user_id, DROP event_id');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7F675F31B');
        $this->addSql('DROP INDEX IDX_3BAE0AA7F675F31B ON event');
        $this->addSql('ALTER TABLE event DROP author_id');
    }
}
