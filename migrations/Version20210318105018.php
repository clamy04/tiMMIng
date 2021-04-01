<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210318105018 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `group` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(10) NOT NULL, semester VARCHAR(10) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, campain VARCHAR(15) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, year VARCHAR(10) DEFAULT NULL, campain VARCHAR(15) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, teacher_id INT DEFAULT NULL, module_id INT DEFAULT NULL, description LONGTEXT NOT NULL, deadline DATE DEFAULT NULL, visible TINYINT(1) NOT NULL, INDEX IDX_527EDB2541807E1D (teacher_id), INDEX IDX_527EDB25AFC2B591 (module_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task_group (task_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_AA645FE58DB60186 (task_id), INDEX IDX_AA645FE5FE54D947 (group_id), PRIMARY KEY(task_id, group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE teacher (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE teacher_module (teacher_id INT NOT NULL, module_id INT NOT NULL, INDEX IDX_19AFF4DD41807E1D (teacher_id), INDEX IDX_19AFF4DDAFC2B591 (module_id), PRIMARY KEY(teacher_id, module_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB2541807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
        $this->addSql('ALTER TABLE task_group ADD CONSTRAINT FK_AA645FE58DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task_group ADD CONSTRAINT FK_AA645FE5FE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE teacher_module ADD CONSTRAINT FK_19AFF4DD41807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE teacher_module ADD CONSTRAINT FK_19AFF4DDAFC2B591 FOREIGN KEY (module_id) REFERENCES module (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task_group DROP FOREIGN KEY FK_AA645FE5FE54D947');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25AFC2B591');
        $this->addSql('ALTER TABLE teacher_module DROP FOREIGN KEY FK_19AFF4DDAFC2B591');
        $this->addSql('ALTER TABLE task_group DROP FOREIGN KEY FK_AA645FE58DB60186');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB2541807E1D');
        $this->addSql('ALTER TABLE teacher_module DROP FOREIGN KEY FK_19AFF4DD41807E1D');
        $this->addSql('DROP TABLE `group`');
        $this->addSql('DROP TABLE module');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE task_group');
        $this->addSql('DROP TABLE teacher');
        $this->addSql('DROP TABLE teacher_module');
        $this->addSql('DROP TABLE user');
    }
}
