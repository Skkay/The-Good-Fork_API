<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210507220018 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_menu DROP FOREIGN KEY FK_30F4008414041B84');
        $this->addSql('DROP INDEX IDX_30F4008414041B84 ON order_menu');
        $this->addSql('ALTER TABLE order_menu CHANGE menus_id menu_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_menu ADD CONSTRAINT FK_30F40084CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('CREATE INDEX IDX_30F40084CCD7E912 ON order_menu (menu_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_menu DROP FOREIGN KEY FK_30F40084CCD7E912');
        $this->addSql('DROP INDEX IDX_30F40084CCD7E912 ON order_menu');
        $this->addSql('ALTER TABLE order_menu CHANGE menu_id menus_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_menu ADD CONSTRAINT FK_30F4008414041B84 FOREIGN KEY (menus_id) REFERENCES menu (id)');
        $this->addSql('CREATE INDEX IDX_30F4008414041B84 ON order_menu (menus_id)');
    }
}
