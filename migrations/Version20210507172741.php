<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210507172741 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE order_drink');
        $this->addSql('DROP TABLE order_food');
        $this->addSql('ALTER TABLE order_menu DROP FOREIGN KEY FK_30F400848D9F6D38');
        $this->addSql('ALTER TABLE order_menu DROP FOREIGN KEY FK_30F40084CCD7E912');
        $this->addSql('DROP INDEX IDX_30F400848D9F6D38 ON order_menu');
        $this->addSql('DROP INDEX IDX_30F40084CCD7E912 ON order_menu');
        $this->addSql('ALTER TABLE order_menu ADD id INT AUTO_INCREMENT NOT NULL, ADD menus_id INT NOT NULL, ADD order__id INT NOT NULL, ADD quantity INT NOT NULL, DROP order_id, DROP menu_id, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE order_menu ADD CONSTRAINT FK_30F4008414041B84 FOREIGN KEY (menus_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE order_menu ADD CONSTRAINT FK_30F40084251A8A50 FOREIGN KEY (order__id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_30F4008414041B84 ON order_menu (menus_id)');
        $this->addSql('CREATE INDEX IDX_30F40084251A8A50 ON order_menu (order__id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_drink (order_id INT NOT NULL, drink_id INT NOT NULL, INDEX IDX_8E20342C8D9F6D38 (order_id), INDEX IDX_8E20342C36AA4BB4 (drink_id), PRIMARY KEY(order_id, drink_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE order_food (order_id INT NOT NULL, food_id INT NOT NULL, INDEX IDX_99C913E08D9F6D38 (order_id), INDEX IDX_99C913E0BA8E87C4 (food_id), PRIMARY KEY(order_id, food_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE order_drink ADD CONSTRAINT FK_8E20342C36AA4BB4 FOREIGN KEY (drink_id) REFERENCES drink (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_drink ADD CONSTRAINT FK_8E20342C8D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_food ADD CONSTRAINT FK_99C913E08D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_food ADD CONSTRAINT FK_99C913E0BA8E87C4 FOREIGN KEY (food_id) REFERENCES food (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_menu MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE order_menu DROP FOREIGN KEY FK_30F4008414041B84');
        $this->addSql('ALTER TABLE order_menu DROP FOREIGN KEY FK_30F40084251A8A50');
        $this->addSql('DROP INDEX IDX_30F4008414041B84 ON order_menu');
        $this->addSql('DROP INDEX IDX_30F40084251A8A50 ON order_menu');
        $this->addSql('ALTER TABLE order_menu DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE order_menu ADD order_id INT NOT NULL, ADD menu_id INT NOT NULL, DROP id, DROP menus_id, DROP order__id, DROP quantity');
        $this->addSql('ALTER TABLE order_menu ADD CONSTRAINT FK_30F400848D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_menu ADD CONSTRAINT FK_30F40084CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_30F400848D9F6D38 ON order_menu (order_id)');
        $this->addSql('CREATE INDEX IDX_30F40084CCD7E912 ON order_menu (menu_id)');
        $this->addSql('ALTER TABLE order_menu ADD PRIMARY KEY (order_id, menu_id)');
    }
}
