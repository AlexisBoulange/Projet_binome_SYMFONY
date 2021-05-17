<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210517143234 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE programmer (id INT AUTO_INCREMENT NOT NULL, sessions_id INT NOT NULL, modules_id INT NOT NULL, duree DATETIME NOT NULL, INDEX IDX_4136CCA9F17C4D8C (sessions_id), INDEX IDX_4136CCA960D6DC42 (modules_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE programmer ADD CONSTRAINT FK_4136CCA9F17C4D8C FOREIGN KEY (sessions_id) REFERENCES session (id)');
        $this->addSql('ALTER TABLE programmer ADD CONSTRAINT FK_4136CCA960D6DC42 FOREIGN KEY (modules_id) REFERENCES module (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE programmer');
    }
}
