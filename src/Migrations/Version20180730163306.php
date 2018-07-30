<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180730163306 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE proprietario (id INT AUTO_INCREMENT NOT NULL, id_pessoa_fisica_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_3073F7C365F3E126 (id_pessoa_fisica_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE proprietario_pessoa_juridica (proprietario_id INT NOT NULL, pessoa_juridica_id INT NOT NULL, INDEX IDX_1350EDA26759BAE5 (proprietario_id), INDEX IDX_1350EDA2A894CDDE (pessoa_juridica_id), PRIMARY KEY(proprietario_id, pessoa_juridica_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phone (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, number INT NOT NULL, ddd INT NOT NULL, INDEX IDX_444F97DD7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE proprietario ADD CONSTRAINT FK_3073F7C365F3E126 FOREIGN KEY (id_pessoa_fisica_id) REFERENCES pessoa_fisica (id)');
        $this->addSql('ALTER TABLE proprietario_pessoa_juridica ADD CONSTRAINT FK_1350EDA26759BAE5 FOREIGN KEY (proprietario_id) REFERENCES proprietario (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE proprietario_pessoa_juridica ADD CONSTRAINT FK_1350EDA2A894CDDE FOREIGN KEY (pessoa_juridica_id) REFERENCES pessoa_juridica (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE phone ADD CONSTRAINT FK_444F97DD7E3C61F9 FOREIGN KEY (owner_id) REFERENCES pessoa_fisica (id)');
        $this->addSql('ALTER TABLE pessoa_fisica CHANGE last_update last_update DATETIME NOT NULL, CHANGE create_date create_date DATETIME NOT NULL, CHANGE last_name last_name VARCHAR(255) DEFAULT NULL, CHANGE cpf cpf VARCHAR(255) DEFAULT NULL, CHANGE rg rg VARCHAR(255) DEFAULT NULL, CHANGE birth_date birth_date VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE municipio CHANGE idUf idUf INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pessoa_juridica CHANGE last_update last_update DATETIME NOT NULL, CHANGE create_date create_date DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE proprietario_pessoa_juridica DROP FOREIGN KEY FK_1350EDA26759BAE5');
        $this->addSql('DROP TABLE proprietario');
        $this->addSql('DROP TABLE proprietario_pessoa_juridica');
        $this->addSql('DROP TABLE phone');
        $this->addSql('ALTER TABLE municipio CHANGE idUf idUf INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pessoa_fisica CHANGE last_update last_update DATETIME NOT NULL, CHANGE create_date create_date DATETIME NOT NULL, CHANGE last_name last_name VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE cpf cpf VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE rg rg VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE birth_date birth_date VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE pessoa_juridica CHANGE last_update last_update DATETIME NOT NULL, CHANGE create_date create_date DATETIME NOT NULL');
    }
}
