<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180730153047 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE departament (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_34F6FDA35E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE estado (id INT AUTO_INCREMENT NOT NULL, codigoUf INT NOT NULL, nome VARCHAR(50) NOT NULL, uf VARCHAR(2) NOT NULL, regiao INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE municipio (id INT AUTO_INCREMENT NOT NULL, uf VARCHAR(2) DEFAULT NULL, codigo INT NOT NULL, nome VARCHAR(255) NOT NULL, INDEX IDX_FE98F5E0B7405B21 (uf), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pessoa_fisica (id INT AUTO_INCREMENT NOT NULL, last_update DATETIME NOT NULL, active TINYINT(1) NOT NULL, create_date DATETIME NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) DEFAULT NULL, cpf VARCHAR(255) DEFAULT NULL, rg VARCHAR(255) DEFAULT NULL, genre VARCHAR(255) NOT NULL, birth_date VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pessoa_juridica (id INT AUTO_INCREMENT NOT NULL, last_update DATETIME NOT NULL, active TINYINT(1) NOT NULL, create_date DATETIME NOT NULL, razao_social VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unit (id INT AUTO_INCREMENT NOT NULL, initials VARCHAR(2) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE municipio ADD CONSTRAINT FK_FE98F5E0B7405B21 FOREIGN KEY (uf) REFERENCES estado (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE municipio DROP FOREIGN KEY FK_FE98F5E0B7405B21');
        $this->addSql('DROP TABLE departament');
        $this->addSql('DROP TABLE estado');
        $this->addSql('DROP TABLE municipio');
        $this->addSql('DROP TABLE pessoa_fisica');
        $this->addSql('DROP TABLE pessoa_juridica');
        $this->addSql('DROP TABLE unit');
    }
}
