<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151002133004 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ProjectBan (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, comment VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_65C24F79166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ProjectBan ADD CONSTRAINT FK_65C24F79166D1F9C FOREIGN KEY (project_id) REFERENCES Work (id)');
        $this->addSql('ALTER TABLE AbstractComment CHANGE text text LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE Party DROP audienceChoiceRating');
        $this->addSql('ALTER TABLE Work ADD windowsBuild VARCHAR(255) DEFAULT NULL, ADD macBuild VARCHAR(255) DEFAULT NULL, ADD linuxBuild VARCHAR(255) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE ProjectBan');
        $this->addSql('ALTER TABLE AbstractComment CHANGE text text VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE Party ADD audienceChoiceRating TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE Work DROP windowsBuild, DROP macBuild, DROP linuxBuild');
    }
}
