<?php

namespace App\Entity;

use App\Repository\PokemonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PokemonRepository::class)]
class Pokemon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $pokedexNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $sprite = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $type = [];

    #[ORM\ManyToMany(targetEntity: Profile::class, inversedBy: 'pokemon')]
    private Collection $trainer_id;

    public function __construct()
    {
        $this->trainer_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPokedexNumber(): ?int
    {
        return $this->pokedexNumber;
    }

    public function setPokedexNumber(int $pokedexNumber): self
    {
        $this->pokedexNumber = $pokedexNumber;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSprite(): ?string
    {
        return $this->sprite;
    }

    public function setSprite(string $sprite): self
    {
        $this->sprite = $sprite;

        return $this;
    }

    public function getType(): array
    {
        return $this->type;
    }

    public function setType(array $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Profile>
     */
    public function getTrainerId(): Collection
    {
        return $this->trainer_id;
    }

    public function addTrainerId(Profile $trainerId): self
    {
        if (!$this->trainer_id->contains($trainerId)) {
            $this->trainer_id->add($trainerId);
        }

        return $this;
    }

    public function removeTrainerId(Profile $trainerId): self
    {
        $this->trainer_id->removeElement($trainerId);

        return $this;
    }
}
