<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180727140330 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pessoa_fisica (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, lastname VARCHAR(255) DEFAULT NULL, cpf VARCHAR(255) DEFAULT NULL, rg VARCHAR(255) DEFAULT NULL, genre VARCHAR(255) NOT NULL, birth_date VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE estado');
        $this->addSql('DROP TABLE municipio');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE estado (Id INT AUTO_INCREMENT NOT NULL, CodigoUf INT NOT NULL, Nome VARCHAR(50) NOT NULL COLLATE utf8_general_ci, Uf CHAR(2) NOT NULL COLLATE utf8_general_ci, Regiao INT NOT NULL, PRIMARY KEY(Id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE municipio (Id INT AUTO_INCREMENT NOT NULL, Codigo INT NOT NULL, Nome VARCHAR(255) NOT NULL COLLATE utf8_general_ci, Uf CHAR(2) NOT NULL COLLATE utf8_general_ci, PRIMARY KEY(Id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE pessoa_fisica');
    }
}
