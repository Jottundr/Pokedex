<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230202193139 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pokemon_profile (pokemon_id INT NOT NULL, profile_id INT NOT NULL, INDEX IDX_ED3DD69E2FE71C3E (pokemon_id), INDEX IDX_ED3DD69ECCFA12B8 (profile_id), PRIMARY KEY(pokemon_id, profile_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pokemon_profile ADD CONSTRAINT FK_ED3DD69E2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pokemon_profile ADD CONSTRAINT FK_ED3DD69ECCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon_profile DROP FOREIGN KEY FK_ED3DD69E2FE71C3E');
        $this->addSql('ALTER TABLE pokemon_profile DROP FOREIGN KEY FK_ED3DD69ECCFA12B8');
        $this->addSql('DROP TABLE pokemon_profile');
    }
}
