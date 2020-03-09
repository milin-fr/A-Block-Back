<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200309123231 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE exercise (id INT AUTO_INCREMENT NOT NULL, mastery_level_id INT NOT NULL, title VARCHAR(64) NOT NULL, time SMALLINT NOT NULL, img_path VARCHAR(64) DEFAULT NULL, description LONGTEXT DEFAULT NULL, score SMALLINT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_AEDAD51C40ADDE1D (mastery_level_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exercise_hint (exercise_id INT NOT NULL, hint_id INT NOT NULL, INDEX IDX_6DE361E1E934951A (exercise_id), INDEX IDX_6DE361E1519161AB (hint_id), PRIMARY KEY(exercise_id, hint_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exercise_prerequisite (exercise_id INT NOT NULL, prerequisite_id INT NOT NULL, INDEX IDX_AF0C1711E934951A (exercise_id), INDEX IDX_AF0C1711276AF86B (prerequisite_id), PRIMARY KEY(exercise_id, prerequisite_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exercise_comment (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, exercise_id INT NOT NULL, text LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_4E61A529A76ED395 (user_id), INDEX IDX_4E61A529E934951A (exercise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hint (id INT AUTO_INCREMENT NOT NULL, text LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mastery_level (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, level_index SMALLINT NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prerequisite (id INT AUTO_INCREMENT NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE program (id INT AUTO_INCREMENT NOT NULL, mastery_level_id INT DEFAULT NULL, title VARCHAR(64) NOT NULL, description LONGTEXT DEFAULT NULL, time SMALLINT NOT NULL, img_path VARCHAR(64) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_92ED778440ADDE1D (mastery_level_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE program_exercise (program_id INT NOT NULL, exercise_id INT NOT NULL, INDEX IDX_2FEF29293EB8070A (program_id), INDEX IDX_2FEF2929E934951A (exercise_id), PRIMARY KEY(program_id, exercise_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE program_comment (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, program_id INT NOT NULL, text LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_1F9EAAB8A76ED395 (user_id), INDEX IDX_1F9EAAB83EB8070A (program_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, mastery_level_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, account_name VARCHAR(64) NOT NULL, img_path VARCHAR(64) DEFAULT NULL, available_time SMALLINT DEFAULT NULL, score INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D64940ADDE1D (mastery_level_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE program_bookmark (user_id INT NOT NULL, program_id INT NOT NULL, INDEX IDX_5B576E28A76ED395 (user_id), INDEX IDX_5B576E283EB8070A (program_id), PRIMARY KEY(user_id, program_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exercise_bookmark (user_id INT NOT NULL, exercise_id INT NOT NULL, INDEX IDX_DC0E32F5A76ED395 (user_id), INDEX IDX_DC0E32F5E934951A (exercise_id), PRIMARY KEY(user_id, exercise_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE followed_program_bookmark (user_id INT NOT NULL, program_id INT NOT NULL, INDEX IDX_9157B34EA76ED395 (user_id), INDEX IDX_9157B34E3EB8070A (program_id), PRIMARY KEY(user_id, program_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE exercise ADD CONSTRAINT FK_AEDAD51C40ADDE1D FOREIGN KEY (mastery_level_id) REFERENCES mastery_level (id)');
        $this->addSql('ALTER TABLE exercise_hint ADD CONSTRAINT FK_6DE361E1E934951A FOREIGN KEY (exercise_id) REFERENCES exercise (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE exercise_hint ADD CONSTRAINT FK_6DE361E1519161AB FOREIGN KEY (hint_id) REFERENCES hint (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE exercise_prerequisite ADD CONSTRAINT FK_AF0C1711E934951A FOREIGN KEY (exercise_id) REFERENCES exercise (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE exercise_prerequisite ADD CONSTRAINT FK_AF0C1711276AF86B FOREIGN KEY (prerequisite_id) REFERENCES prerequisite (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE exercise_comment ADD CONSTRAINT FK_4E61A529A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE exercise_comment ADD CONSTRAINT FK_4E61A529E934951A FOREIGN KEY (exercise_id) REFERENCES exercise (id)');
        $this->addSql('ALTER TABLE program ADD CONSTRAINT FK_92ED778440ADDE1D FOREIGN KEY (mastery_level_id) REFERENCES mastery_level (id)');
        $this->addSql('ALTER TABLE program_exercise ADD CONSTRAINT FK_2FEF29293EB8070A FOREIGN KEY (program_id) REFERENCES program (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE program_exercise ADD CONSTRAINT FK_2FEF2929E934951A FOREIGN KEY (exercise_id) REFERENCES exercise (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE program_comment ADD CONSTRAINT FK_1F9EAAB8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE program_comment ADD CONSTRAINT FK_1F9EAAB83EB8070A FOREIGN KEY (program_id) REFERENCES program (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64940ADDE1D FOREIGN KEY (mastery_level_id) REFERENCES mastery_level (id)');
        $this->addSql('ALTER TABLE program_bookmark ADD CONSTRAINT FK_5B576E28A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE program_bookmark ADD CONSTRAINT FK_5B576E283EB8070A FOREIGN KEY (program_id) REFERENCES program (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE exercise_bookmark ADD CONSTRAINT FK_DC0E32F5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE exercise_bookmark ADD CONSTRAINT FK_DC0E32F5E934951A FOREIGN KEY (exercise_id) REFERENCES exercise (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE followed_program_bookmark ADD CONSTRAINT FK_9157B34EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE followed_program_bookmark ADD CONSTRAINT FK_9157B34E3EB8070A FOREIGN KEY (program_id) REFERENCES program (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE exercise_hint DROP FOREIGN KEY FK_6DE361E1E934951A');
        $this->addSql('ALTER TABLE exercise_prerequisite DROP FOREIGN KEY FK_AF0C1711E934951A');
        $this->addSql('ALTER TABLE exercise_comment DROP FOREIGN KEY FK_4E61A529E934951A');
        $this->addSql('ALTER TABLE program_exercise DROP FOREIGN KEY FK_2FEF2929E934951A');
        $this->addSql('ALTER TABLE exercise_bookmark DROP FOREIGN KEY FK_DC0E32F5E934951A');
        $this->addSql('ALTER TABLE exercise_hint DROP FOREIGN KEY FK_6DE361E1519161AB');
        $this->addSql('ALTER TABLE exercise DROP FOREIGN KEY FK_AEDAD51C40ADDE1D');
        $this->addSql('ALTER TABLE program DROP FOREIGN KEY FK_92ED778440ADDE1D');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64940ADDE1D');
        $this->addSql('ALTER TABLE exercise_prerequisite DROP FOREIGN KEY FK_AF0C1711276AF86B');
        $this->addSql('ALTER TABLE program_exercise DROP FOREIGN KEY FK_2FEF29293EB8070A');
        $this->addSql('ALTER TABLE program_comment DROP FOREIGN KEY FK_1F9EAAB83EB8070A');
        $this->addSql('ALTER TABLE program_bookmark DROP FOREIGN KEY FK_5B576E283EB8070A');
        $this->addSql('ALTER TABLE followed_program_bookmark DROP FOREIGN KEY FK_9157B34E3EB8070A');
        $this->addSql('ALTER TABLE exercise_comment DROP FOREIGN KEY FK_4E61A529A76ED395');
        $this->addSql('ALTER TABLE program_comment DROP FOREIGN KEY FK_1F9EAAB8A76ED395');
        $this->addSql('ALTER TABLE program_bookmark DROP FOREIGN KEY FK_5B576E28A76ED395');
        $this->addSql('ALTER TABLE exercise_bookmark DROP FOREIGN KEY FK_DC0E32F5A76ED395');
        $this->addSql('ALTER TABLE followed_program_bookmark DROP FOREIGN KEY FK_9157B34EA76ED395');
        $this->addSql('DROP TABLE exercise');
        $this->addSql('DROP TABLE exercise_hint');
        $this->addSql('DROP TABLE exercise_prerequisite');
        $this->addSql('DROP TABLE exercise_comment');
        $this->addSql('DROP TABLE hint');
        $this->addSql('DROP TABLE mastery_level');
        $this->addSql('DROP TABLE prerequisite');
        $this->addSql('DROP TABLE program');
        $this->addSql('DROP TABLE program_exercise');
        $this->addSql('DROP TABLE program_comment');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE program_bookmark');
        $this->addSql('DROP TABLE exercise_bookmark');
        $this->addSql('DROP TABLE followed_program_bookmark');
    }
}
