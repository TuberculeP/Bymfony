<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CommandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use Doctrine\ORM\Mapping as ORM;
#[ApiResource(
    operations: [
        new GetCollection(security: "is_granted('ROLE_BARMAN')"),
        new Post(security: "is_granted('ROLE_WAITER')"),
        new Get(security: "is_granted('ROLE_BARMAN', 'ROLE_WAITER')"),
        new Patch(security: "is_granted('ROLE_WAITER')"),
    ],
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']]
)]
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
     * @var Collection<int, Drink>
     */
    #[ORM\ManyToMany(targetEntity: Drink::class)]
    private Collection $drinks;

    public function __construct()
    {
        $this->drinks = new ArrayCollection();
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
     * @return Collection<int, Drink>
     */
    public function getDrinks(): Collection
    {
        return $this->drinks;
    }

    public function addDrink(Drink $drink): static
    {
        if (!$this->drinks->contains($drink)) {
            $this->drinks->add($drink);
        }

        return $this;
    }

    public function removeDrink(Drink $drink): static
    {
        $this->drinks->removeElement($drink);

        return $this;
    }
}
