<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210324192210 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_7D053A935E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_drink (menu_id INT NOT NULL, drink_id INT NOT NULL, INDEX IDX_9C1263B6CCD7E912 (menu_id), INDEX IDX_9C1263B636AA4BB4 (drink_id), PRIMARY KEY(menu_id, drink_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_food (menu_id INT NOT NULL, food_id INT NOT NULL, INDEX IDX_1C77D9B9CCD7E912 (menu_id), INDEX IDX_1C77D9B9BA8E87C4 (food_id), PRIMARY KEY(menu_id, food_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE menu_drink ADD CONSTRAINT FK_9C1263B6CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_drink ADD CONSTRAINT FK_9C1263B636AA4BB4 FOREIGN KEY (drink_id) REFERENCES drink (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_food ADD CONSTRAINT FK_1C77D9B9CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_food ADD CONSTRAINT FK_1C77D9B9BA8E87C4 FOREIGN KEY (food_id) REFERENCES food (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE menu_drink DROP FOREIGN KEY FK_9C1263B6CCD7E912');
        $this->addSql('ALTER TABLE menu_food DROP FOREIGN KEY FK_1C77D9B9CCD7E912');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE menu_drink');
        $this->addSql('DROP TABLE menu_food');
    }
}
