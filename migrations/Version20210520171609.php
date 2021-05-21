<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210520171609 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, service_id INT NOT NULL, table__id INT NOT NULL, date DATE NOT NULL, INDEX IDX_42C84955A76ED395 (user_id), INDEX IDX_42C84955ED5CA9E6 (service_id), INDEX IDX_42C849556FA44F54 (table__id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, start_time INT NOT NULL, end_time INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `table` (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, place INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849556FA44F54 FOREIGN KEY (table__id) REFERENCES `table` (id)');
        $this->addSql('ALTER TABLE order_drink ADD CONSTRAINT FK_8E20342C36AA4BB4 FOREIGN KEY (drink_id) REFERENCES drink (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_drink ADD CONSTRAINT FK_8E20342C251A8A50 FOREIGN KEY (order__id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_food ADD CONSTRAINT FK_99C913E0BA8E87C4 FOREIGN KEY (food_id) REFERENCES food (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_food ADD CONSTRAINT FK_99C913E0251A8A50 FOREIGN KEY (order__id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_menu ADD CONSTRAINT FK_30F40084CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_menu ADD CONSTRAINT FK_30F40084251A8A50 FOREIGN KEY (order__id) REFERENCES `order` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955ED5CA9E6');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849556FA44F54');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE `table`');
        $this->addSql('ALTER TABLE order_drink DROP FOREIGN KEY FK_8E20342C36AA4BB4');
        $this->addSql('ALTER TABLE order_drink DROP FOREIGN KEY FK_8E20342C251A8A50');
        $this->addSql('ALTER TABLE order_food DROP FOREIGN KEY FK_99C913E0BA8E87C4');
        $this->addSql('ALTER TABLE order_food DROP FOREIGN KEY FK_99C913E0251A8A50');
        $this->addSql('ALTER TABLE order_menu DROP FOREIGN KEY FK_30F40084CCD7E912');
        $this->addSql('ALTER TABLE order_menu DROP FOREIGN KEY FK_30F40084251A8A50');
    }
}
