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
use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use App\EventListener\SecurityAwareInterface;
use App\State\AutoAuthorCommandProcessor;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new GetCollection(security: "is_granted('ROLE_BARMAN') or is_granted('ROLE_WAITER')"),
        new Post(security: "is_granted('ROLE_WAITER')", processor: AutoAuthorCommandProcessor::class),
        new Get(security: "is_granted('ROLE_BARMAN') or is_granted('ROLE_WAITER')"),
        new Patch(security: "is_granted('ROLE_WAITER') or is_granted('ROLE_BARMAN')"),
    ],
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']]
)]
#[ORM\Entity(repositoryClass: CommandRepository::class)]
class Command implements SecurityAwareInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('read')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'commands')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read'])]
    private ?User $author = null;

    #[ORM\Column]
    #[Groups(['read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 100)]
    #[Groups(['read', 'write'])]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'assigned_commands')]
    #[Groups(['read', 'write'])]
    #[Assert\NotBlank]
    private ?User $assignee = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['read'])]
    private ?\DateTimeImmutable $readyAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['read'])]
    private ?\DateTimeImmutable $paidAt = null;

    /**
     * @var Collection<int, Drink>
     */
    #[ORM\ManyToMany(targetEntity: Drink::class)]
    #[Groups(['read', 'write'])]
    private Collection $drinks;

    #[ORM\Column]
    #[Groups(['read', 'write'])]
    #[Assert\NotBlank]
    private ?int $tableNumber = null;

    /** Pour vérifier les rôles on injecte Security */
    private ?Security $security = null;

    public function setSecurity(Security $security): void
    {
        $this->security = $security;
    }

    public function __construct()
    {
        $this->drinks = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->status = 'pending';
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
        switch ($status) {
            case 'pending':
                if (!$this->security->isGranted("ROLE_WAITER")) {
                    throw new AccessDeniedException("Only waiters can mark commands as pending");
                }
                if ($this->paidAt !== null) {
                    throw new AccessDeniedException("Cannot mark a paid command as pending");
                }
                break;
            case 'ready':
                if (!$this->security->isGranted("ROLE_BARMAN") || $this->paidAt !== null) {
                    throw new AccessDeniedException("Only barmen can mark commands as ready");
                }
                if ($this->paidAt !== null) {
                    throw new AccessDeniedException("Cannot mark a paid command as ready");
                }
                $this->setReadyAt(new \DateTimeImmutable());
                break;
            case 'paid':
                if (!$this->security->isGranted("ROLE_WAITER")) {
                    throw new AccessDeniedException("Only waiters can mark commands as paid");
                }
                if ($this->paidAt !== null) {
                    throw new AccessDeniedException("Command is already paid");
                }
                $this->setPaidAt(new \DateTimeImmutable());
                break;
            default:
                throw new \InvalidArgumentException("Invalid status");
        }
        
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
        if (!$this->security->isGranted("ROLE_WAITER")) {
            throw new AccessDeniedException("Only waiters can modify drinks in commands");
        }
        if ($this->paidAt !== null) {
            throw new AccessDeniedException("Cannot modify drinks of a paid command");
        }
        $this->drinks->add($drink);
        return $this;
    }

    public function removeDrink(Drink $drink): static
    {
        if (!$this->security->isGranted("ROLE_WAITER")) {
            throw new AccessDeniedException("Only waiters can modify drinks in commands");
        }
        if ($this->paidAt !== null) {
            throw new AccessDeniedException("Cannot modify drinks of a paid command");
        }
        $this->drinks->removeElement($drink);
        return $this;
    }

    public function getTableNumber(): ?int
    {
        return $this->tableNumber;
    }

    public function setTableNumber(int $tableNumber): static
    {
        if (!$this->security->isGranted("ROLE_WAITER")) {
            throw new AccessDeniedException("Only waiters can modify tables in commands");
        }
        if ($this->paidAt !== null) {
            throw new AccessDeniedException("Cannot modify table number of a paid command");
        }
        $this->tableNumber = $tableNumber;

        return $this;
    }
}
