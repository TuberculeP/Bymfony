<?php

namespace App\Entity;

use App\Repository\CommandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandRepository::class)]
class Command
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'commands')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 100)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'assigned_commands')]
    private ?User $assignee = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $readyAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $paidAt = null;

    /**
     * @var Collection<int, DrinkEntry>
     */
    #[ORM\OneToMany(targetEntity: DrinkEntry::class, mappedBy: 'command', orphanRemoval: true)]
    private Collection $drinkEntry;

    public function __construct()
    {
        $this->drinkEntry = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getAssignee(): ?User
    {
        return $this->assignee;
    }

    public function setAssignee(?User $assignee): static
    {
        $this->assignee = $assignee;

        return $this;
    }

    public function getReadyAt(): ?\DateTimeImmutable
    {
        return $this->readyAt;
    }

    public function setReadyAt(?\DateTimeImmutable $readyAt): static
    {
        $this->readyAt = $readyAt;

        return $this;
    }

    public function getPaidAt(): ?\DateTimeImmutable
    {
        return $this->paidAt;
    }

    public function setPaidAt(?\DateTimeImmutable $paidAt): static
    {
        $this->paidAt = $paidAt;

        return $this;
    }

    /**
     * @return Collection<int, DrinkEntry>
     */
    public function getDrinkEntry(): Collection
    {
        return $this->drinkEntry;
    }

    public function addDrinkEntry(DrinkEntry $drinkEntry): static
    {
        if (!$this->drinkEntry->contains($drinkEntry)) {
            $this->drinkEntry->add($drinkEntry);
            $drinkEntry->setCommand($this);
        }

        return $this;
    }

    public function removeDrinkEntry(DrinkEntry $drinkEntry): static
    {
        if ($this->drinkEntry->removeElement($drinkEntry)) {
            // set the owning side to null (unless already changed)
            if ($drinkEntry->getCommand() === $this) {
                $drinkEntry->setCommand(null);
            }
        }

        return $this;
    }
}
