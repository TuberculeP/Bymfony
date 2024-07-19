<?php

namespace App\Entity;

use App\Repository\DrinkEntryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DrinkEntryRepository::class)]
class DrinkEntry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $amount = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Drink $drink = null;

    #[ORM\ManyToOne(inversedBy: 'drinkEntry')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Command $command = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDrink(): ?Drink
    {
        return $this->drink;
    }

    public function setDrink(?Drink $drink): static
    {
        $this->drink = $drink;

        return $this;
    }

    public function getCommand(): ?Command
    {
        return $this->command;
    }

    public function setCommand(?Command $command): static
    {
        $this->command = $command;

        return $this;
    }
}
