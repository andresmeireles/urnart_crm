<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180730164110 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE proprietario DROP name, CHANGE pessoa_fisica_id pessoa_fisica_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pessoa_fisica CHANGE last_update last_update DATETIME NOT NULL, CHANGE create_date create_date DATETIME NOT NULL, CHANGE last_name last_name VARCHAR(255) DEFAULT NULL, CHANGE cpf cpf VARCHAR(255) DEFAULT NULL, CHANGE rg rg VARCHAR(255) DEFAULT NULL, CHANGE birth_date birth_date VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE email ADD last_update DATETIME NOT NULL, ADD active TINYINT(1) NOT NULL, ADD create_date DATETIME NOT NULL, CHANGE owner_id owner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE phone ADD last_update DATETIME NOT NULL, ADD active TINYINT(1) NOT NULL, ADD create_date DATETIME NOT NULL, CHANGE owner_id owner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE municipio CHANGE idUf idUf INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pessoa_juridica CHANGE last_update last_update DATETIME NOT NULL, CHANGE create_date create_date DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE email DROP last_update, DROP active, DROP create_date, CHANGE owner_id owner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE municipio CHANGE idUf idUf INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pessoa_fisica CHANGE last_update last_update DATETIME NOT NULL, CHANGE create_date create_date DATETIME NOT NULL, CHANGE last_name last_name VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE cpf cpf VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE rg rg VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE birth_date birth_date VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE pessoa_juridica CHANGE last_update last_update DATETIME NOT NULL, CHANGE create_date create_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE phone DROP last_update, DROP active, DROP create_date, CHANGE owner_id owner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE proprietario ADD name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE pessoa_fisica_id pessoa_fisica_id INT DEFAULT NULL');
    }
}
