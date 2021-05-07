<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210507223717 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_drink DROP FOREIGN KEY FK_8E20342C251A8A50');
        $this->addSql('ALTER TABLE order_drink ADD CONSTRAINT FK_8E20342C251A8A50 FOREIGN KEY (order__id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_food DROP FOREIGN KEY FK_99C913E0251A8A50');
        $this->addSql('ALTER TABLE order_food ADD CONSTRAINT FK_99C913E0251A8A50 FOREIGN KEY (order__id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_menu DROP FOREIGN KEY FK_30F40084251A8A50');
        $this->addSql('ALTER TABLE order_menu ADD CONSTRAINT FK_30F40084251A8A50 FOREIGN KEY (order__id) REFERENCES `order` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_drink DROP FOREIGN KEY FK_8E20342C251A8A50');
        $this->addSql('ALTER TABLE order_drink ADD CONSTRAINT FK_8E20342C251A8A50 FOREIGN KEY (order__id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE order_food DROP FOREIGN KEY FK_99C913E0251A8A50');
        $this->addSql('ALTER TABLE order_food ADD CONSTRAINT FK_99C913E0251A8A50 FOREIGN KEY (order__id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE order_menu DROP FOREIGN KEY FK_30F40084251A8A50');
        $this->addSql('ALTER TABLE order_menu ADD CONSTRAINT FK_30F40084251A8A50 FOREIGN KEY (order__id) REFERENCES `order` (id)');
    }
}
