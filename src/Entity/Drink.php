<?php

namespace App\Entity;

use App\Repository\DrinkRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DrinkRepository::class)]
class Drink
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\OneToOne(mappedBy: 'drink', cascade: ['persist', 'remove'])]
    private ?Media $media = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getMedia(): ?Media
    {
        return $this->media;
    }

    public function setMedia(?Media $media): static
    {
        // unset the owning side of the relation if necessary
        if ($media === null && $this->media !== null) {
            $this->media->setDrink(null);
        }

        // set the owning side of the relation if necessary
        if ($media !== null && $media->getDrink() !== $this) {
            $media->setDrink($this);
        }

        $this->media = $media;

        return $this;
    }
}
