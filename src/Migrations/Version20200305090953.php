<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200305090953 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE access_level CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE exercise ADD mastery_level_id INT NOT NULL, CHANGE img_path img_path VARCHAR(64) DEFAULT NULL, CHANGE score score SMALLINT DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE exercise ADD CONSTRAINT FK_AEDAD51C40ADDE1D FOREIGN KEY (mastery_level_id) REFERENCES mastery_level (id)');
        $this->addSql('CREATE INDEX IDX_AEDAD51C40ADDE1D ON exercise (mastery_level_id)');
        $this->addSql('ALTER TABLE hint CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE mastery_level ADD level_index SMALLINT NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE prerequisite CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE program ADD mastery_level_id INT DEFAULT NULL, CHANGE img_path img_path VARCHAR(64) DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE program ADD CONSTRAINT FK_92ED778440ADDE1D FOREIGN KEY (mastery_level_id) REFERENCES mastery_level (id)');
        $this->addSql('CREATE INDEX IDX_92ED778440ADDE1D ON program (mastery_level_id)');
        $this->addSql('ALTER TABLE user CHANGE mastery_level_id mastery_level_id INT DEFAULT NULL, CHANGE img_path img_path VARCHAR(64) DEFAULT NULL, CHANGE available_time available_time SMALLINT DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE access_level CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE exercise DROP FOREIGN KEY FK_AEDAD51C40ADDE1D');
        $this->addSql('DROP INDEX IDX_AEDAD51C40ADDE1D ON exercise');
        $this->addSql('ALTER TABLE exercise DROP mastery_level_id, CHANGE img_path img_path VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE score score SMALLINT DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE hint CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE mastery_level DROP level_index, CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE prerequisite CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE program DROP FOREIGN KEY FK_92ED778440ADDE1D');
        $this->addSql('DROP INDEX IDX_92ED778440ADDE1D ON program');
        $this->addSql('ALTER TABLE program DROP mastery_level_id, CHANGE img_path img_path VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user CHANGE mastery_level_id mastery_level_id INT DEFAULT NULL, CHANGE img_path img_path VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE available_time available_time SMALLINT DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
    }
}
