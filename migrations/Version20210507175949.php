<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210507175949 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_drink (id INT AUTO_INCREMENT NOT NULL, drink_id INT NOT NULL, order__id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_8E20342C36AA4BB4 (drink_id), INDEX IDX_8E20342C251A8A50 (order__id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_food (id INT AUTO_INCREMENT NOT NULL, food_id INT NOT NULL, order__id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_99C913E0BA8E87C4 (food_id), INDEX IDX_99C913E0251A8A50 (order__id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_drink ADD CONSTRAINT FK_8E20342C36AA4BB4 FOREIGN KEY (drink_id) REFERENCES drink (id)');
        $this->addSql('ALTER TABLE order_drink ADD CONSTRAINT FK_8E20342C251A8A50 FOREIGN KEY (order__id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE order_food ADD CONSTRAINT FK_99C913E0BA8E87C4 FOREIGN KEY (food_id) REFERENCES food (id)');
        $this->addSql('ALTER TABLE order_food ADD CONSTRAINT FK_99C913E0251A8A50 FOREIGN KEY (order__id) REFERENCES `order` (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE order_drink');
        $this->addSql('DROP TABLE order_food');
    }
}
