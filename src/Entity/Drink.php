<?php

namespace App\Entity;

use App\Repository\DrinkRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DrinkRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(security: "is_granted('ROLE_BARMAN', 'ROLE_WAITER')"),
        new Post(security: "is_granted('ROLE_BARMAN')"),
        new Get(security: "is_granted('ROLE_BARMAN')"),
        new Patch(security: "is_granted('ROLE_BARMAN')"),
        new Delete(security: "is_granted('ROLE_BARMAN')"),
    ],
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']]
)]
class Drink
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read', 'write'])]
    #[Assert\NotBlank()]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['read', 'write'])]
    #[Assert\NotBlank()]
    private ?float $price = null;

    #[Groups(['read', 'write'])]
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
