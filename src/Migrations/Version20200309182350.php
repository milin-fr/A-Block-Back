<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200309182350 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE exercise CHANGE img_path img_path VARCHAR(64) DEFAULT NULL, CHANGE score score SMALLINT DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE exercise_comment CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE hint CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE mastery_level CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE img_path img_path VARCHAR(64) DEFAULT NULL');
        $this->addSql('ALTER TABLE prerequisite CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE program CHANGE mastery_level_id mastery_level_id INT DEFAULT NULL, CHANGE img_path img_path VARCHAR(64) DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE program_comment CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD active_program_id INT DEFAULT NULL, CHANGE mastery_level_id mastery_level_id INT DEFAULT NULL, CHANGE roles roles JSON NOT NULL, CHANGE img_path img_path VARCHAR(64) DEFAULT NULL, CHANGE available_time available_time SMALLINT DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6491F1A65FC FOREIGN KEY (active_program_id) REFERENCES program (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6491F1A65FC ON user (active_program_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE exercise CHANGE img_path img_path VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE score score SMALLINT DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE exercise_comment CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE hint CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE mastery_level CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\', CHANGE img_path img_path VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE prerequisite CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE program CHANGE mastery_level_id mastery_level_id INT DEFAULT NULL, CHANGE img_path img_path VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE program_comment CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6491F1A65FC');
        $this->addSql('DROP INDEX IDX_8D93D6491F1A65FC ON user');
        $this->addSql('ALTER TABLE user DROP active_program_id, CHANGE mastery_level_id mastery_level_id INT DEFAULT NULL, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE img_path img_path VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE available_time available_time SMALLINT DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
    }
}
