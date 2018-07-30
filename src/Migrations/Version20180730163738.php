<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180730163738 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE email (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, INDEX IDX_E7927C747E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE email ADD CONSTRAINT FK_E7927C747E3C61F9 FOREIGN KEY (owner_id) REFERENCES pessoa_fisica (id)');
        $this->addSql('ALTER TABLE proprietario DROP FOREIGN KEY FK_3073F7C365F3E126');
        $this->addSql('DROP INDEX UNIQ_3073F7C365F3E126 ON proprietario');
        $this->addSql('ALTER TABLE proprietario ADD pessoa_fisica_id INT DEFAULT NULL, DROP id_pessoa_fisica_id');
        $this->addSql('ALTER TABLE proprietario ADD CONSTRAINT FK_3073F7C38679B4F7 FOREIGN KEY (pessoa_fisica_id) REFERENCES pessoa_fisica (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3073F7C38679B4F7 ON proprietario (pessoa_fisica_id)');
        $this->addSql('ALTER TABLE pessoa_fisica CHANGE last_update last_update DATETIME NOT NULL, CHANGE create_date create_date DATETIME NOT NULL, CHANGE last_name last_name VARCHAR(255) DEFAULT NULL, CHANGE cpf cpf VARCHAR(255) DEFAULT NULL, CHANGE rg rg VARCHAR(255) DEFAULT NULL, CHANGE birth_date birth_date VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE phone CHANGE owner_id owner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE municipio CHANGE idUf idUf INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pessoa_juridica CHANGE last_update last_update DATETIME NOT NULL, CHANGE create_date create_date DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE email');
        $this->addSql('ALTER TABLE municipio CHANGE idUf idUf INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pessoa_fisica CHANGE last_update last_update DATETIME NOT NULL, CHANGE create_date create_date DATETIME NOT NULL, CHANGE last_name last_name VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE cpf cpf VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE rg rg VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE birth_date birth_date VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE pessoa_juridica CHANGE last_update last_update DATETIME NOT NULL, CHANGE create_date create_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE phone CHANGE owner_id owner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE proprietario DROP FOREIGN KEY FK_3073F7C38679B4F7');
        $this->addSql('DROP INDEX UNIQ_3073F7C38679B4F7 ON proprietario');
        $this->addSql('ALTER TABLE proprietario ADD id_pessoa_fisica_id INT DEFAULT NULL, DROP pessoa_fisica_id');
        $this->addSql('ALTER TABLE proprietario ADD CONSTRAINT FK_3073F7C365F3E126 FOREIGN KEY (id_pessoa_fisica_id) REFERENCES pessoa_fisica (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3073F7C365F3E126 ON proprietario (id_pessoa_fisica_id)');
    }
}
