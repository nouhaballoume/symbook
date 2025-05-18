<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250516194349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, date_commande DATETIME NOT NULL, mode_paiement VARCHAR(255) NOT NULL, statut VARCHAR(255) NOT NULL, total DOUBLE PRECISION NOT NULL, INDEX IDX_6EEAA67DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE detail_commande (id INT AUTO_INCREMENT NOT NULL, commande_id INT DEFAULT NULL, livre_id INT DEFAULT NULL, quantite INT NOT NULL, INDEX IDX_98344FA682EA2E54 (commande_id), INDEX IDX_98344FA637D925CB (livre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE detail_commande ADD CONSTRAINT FK_98344FA682EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE detail_commande ADD CONSTRAINT FK_98344FA637D925CB FOREIGN KEY (livre_id) REFERENCES livres (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DA76ED395');
        $this->addSql('ALTER TABLE detail_commande DROP FOREIGN KEY FK_98344FA682EA2E54');
        $this->addSql('ALTER TABLE detail_commande DROP FOREIGN KEY FK_98344FA637D925CB');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE detail_commande');
    }
}
