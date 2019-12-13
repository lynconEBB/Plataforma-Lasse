<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191213194400 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Phone DROP FOREIGN KEY FK_858EB8D9CB944F1A');
        $this->addSql('ALTER TABLE course_aluno DROP FOREIGN KEY FK_8E6276E8B2DDF7F4');
        $this->addSql('ALTER TABLE course_aluno DROP FOREIGN KEY FK_8E6276E8591CC992');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, login VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE Aluno');
        $this->addSql('DROP TABLE Course');
        $this->addSql('DROP TABLE Phone');
        $this->addSql('DROP TABLE course_aluno');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Aluno (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE Course (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE Phone (id INT AUTO_INCREMENT NOT NULL, student_id INT DEFAULT NULL, number VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, INDEX IDX_858EB8D9CB944F1A (student_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE course_aluno (course_id INT NOT NULL, aluno_id INT NOT NULL, INDEX IDX_8E6276E8B2DDF7F4 (aluno_id), INDEX IDX_8E6276E8591CC992 (course_id), PRIMARY KEY(course_id, aluno_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE Phone ADD CONSTRAINT FK_858EB8D9CB944F1A FOREIGN KEY (student_id) REFERENCES Aluno (id)');
        $this->addSql('ALTER TABLE course_aluno ADD CONSTRAINT FK_8E6276E8591CC992 FOREIGN KEY (course_id) REFERENCES Course (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE course_aluno ADD CONSTRAINT FK_8E6276E8B2DDF7F4 FOREIGN KEY (aluno_id) REFERENCES Aluno (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE user');
    }
}
