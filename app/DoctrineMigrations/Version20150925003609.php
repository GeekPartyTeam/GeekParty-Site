<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Dropping old tables & modifying comments to hold more text
 */
class Version20150925003609 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->skipIf(!$schema->hasTable('TeamMember'));

        $this->addSql('ALTER TABLE TeamMember DROP FOREIGN KEY FK_752B5942296CD8AE');
        $this->addSql('DROP TABLE Team');
        $this->addSql('DROP TABLE TeamMember');
        $this->addSql('DROP TABLE WorkAuthor');
        $this->addSql('ALTER TABLE AbstractComment CHANGE text text LONGTEXT NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Team (id INT AUTO_INCREMENT NOT NULL, leader_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, description VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, contacts VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, UNIQUE INDEX UNIQ_64D2092173154ED4 (leader_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE TeamMember (id INT AUTO_INCREMENT NOT NULL, team_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, description VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, INDEX IDX_752B5942296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE WorkAuthor (id INT AUTO_INCREMENT NOT NULL, work_id VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, url VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, INDEX IDX_579D499DBB3453DB (work_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Team ADD CONSTRAINT FK_64D2092173154ED4 FOREIGN KEY (leader_id) REFERENCES User (id)');
        $this->addSql('ALTER TABLE TeamMember ADD CONSTRAINT FK_752B5942296CD8AE FOREIGN KEY (team_id) REFERENCES Team (id)');
        $this->addSql('ALTER TABLE AbstractComment CHANGE text text VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
    }
}
