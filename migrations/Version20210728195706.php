<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210728195706 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE account (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, account_number VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, balance DOUBLE PRECISION NOT NULL)');
        $this->addSql('CREATE INDEX IDX_7D3656A4A76ED395 ON account (user_id)');
        $this->addSql('CREATE TABLE transfer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, sender_id INTEGER NOT NULL, receiver_id INTEGER NOT NULL, purpose VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, amount INTEGER NOT NULL, date DATETIME NOT NULL)');
        $this->addSql('CREATE INDEX IDX_4034A3C0F624B39D ON transfer (sender_id)');
        $this->addSql('CREATE INDEX IDX_4034A3C0CD53EDB6 ON transfer (receiver_id)');

        $this->addSql('INSERT INTO account VALUES (1, 1, "LT121000011101001000", "Main", 500)');
        $this->addSql('INSERT INTO account VALUES (2, 1, "LT121000011101001001", "Savings", 500)');
        $this->addSql('INSERT INTO account VALUES (3, 2, "LT121000011101001002", "Main", 500)');
        $this->addSql('INSERT INTO account VALUES (4, 2, "LT121000011101001003", "Savings", 500)');
        $this->addSql('INSERT INTO account VALUES (5, 3, "LT121000011101001004", "Main", 500)');
        $this->addSql('INSERT INTO account VALUES (6, 3, "LT121000011101001005", "Savings", 500)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP TABLE transfer');
    }
}
