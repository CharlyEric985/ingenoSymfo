<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220313224941 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE wms_code (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wms_dirigeant (id INT AUTO_INCREMENT NOT NULL, dirigeant_last_name VARCHAR(100) NOT NULL, dirigeant_first_name VARCHAR(100) NOT NULL, email VARCHAR(180) NOT NULL, sexe VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_91FB250E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wms_file (id INT AUTO_INCREMENT NOT NULL, fl_extension VARCHAR(10) NOT NULL, fl_name VARCHAR(255) NOT NULL, fl_url VARCHAR(255) NOT NULL, fl_nature VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wms_role (id INT AUTO_INCREMENT NOT NULL, rl_name VARCHAR(45) DEFAULT NULL, rl_description VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wms_societe (id INT AUTO_INCREMENT NOT NULL, societe_name VARCHAR(50) NOT NULL, description VARCHAR(500) NOT NULL, types VARCHAR(50) NOT NULL, code_postal VARCHAR(10) NOT NULL, ville VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wms_user (id INT AUTO_INCREMENT NOT NULL, wms_file_id INT DEFAULT NULL, wms_role_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, usr_firstname VARCHAR(200) DEFAULT NULL, usr_lastname VARCHAR(200) DEFAULT NULL, enabled TINYINT(1) DEFAULT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, usr_date_create DATETIME DEFAULT NULL, usr_date_update DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_B2F444E5E7927C74 (email), INDEX IDX_B2F444E5D308C134 (wms_file_id), INDEX IDX_B2F444E596C09AF4 (wms_role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wms_ville (id INT AUTO_INCREMENT NOT NULL, code_id INT DEFAULT NULL, nom_ville VARCHAR(50) NOT NULL, INDEX IDX_9C9C519227DAFE17 (code_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE wms_user ADD CONSTRAINT FK_B2F444E5D308C134 FOREIGN KEY (wms_file_id) REFERENCES wms_file (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE wms_user ADD CONSTRAINT FK_B2F444E596C09AF4 FOREIGN KEY (wms_role_id) REFERENCES wms_role (id)');
        $this->addSql('ALTER TABLE wms_ville ADD CONSTRAINT FK_9C9C519227DAFE17 FOREIGN KEY (code_id) REFERENCES wms_code (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wms_ville DROP FOREIGN KEY FK_9C9C519227DAFE17');
        $this->addSql('ALTER TABLE wms_user DROP FOREIGN KEY FK_B2F444E5D308C134');
        $this->addSql('ALTER TABLE wms_user DROP FOREIGN KEY FK_B2F444E596C09AF4');
        $this->addSql('DROP TABLE wms_code');
        $this->addSql('DROP TABLE wms_dirigeant');
        $this->addSql('DROP TABLE wms_file');
        $this->addSql('DROP TABLE wms_role');
        $this->addSql('DROP TABLE wms_societe');
        $this->addSql('DROP TABLE wms_user');
        $this->addSql('DROP TABLE wms_ville');
    }
}
