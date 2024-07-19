<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use App\State\UserPasswordHasherProcessor;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ORM\Table(name: '`user`')]
#[ApiResource(
    operations: [
        new GetCollection(security: "is_granted('ROLE_PATRON')"),
        new Post(processor: UserPasswordHasherProcessor::class, security: "is_granted('ROLE_PATRON')"),
        new Get(security: "is_granted('ROLE_PATRON') or object == user"),
        new Patch(processor: UserPasswordHasherProcessor::class, security: "is_granted('ROLE_PATRON')"),
        new Delete(security: "is_granted('ROLE_PATRON')"),
    ],
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']]
)]
#[UniqueEntity('fullname')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('read')]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank()]
    #[Groups(['read', 'write'])]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    #[Groups(['read', 'write'])]
    private array $roles = [];

    #[Groups(['write'])]
    #[Assert\NotBlank()]
    private ?string $plainPassword = null;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(['read'])]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read', 'write'])]
    #[Assert\NotBlank()]
    private ?string $fullname = null;

    /**
     * @var Collection<int, Command>
     */
    #[ORM\OneToMany(targetEntity: Command::class, mappedBy: 'author', orphanRemoval: true)]
    private Collection $commands;

    /**
     * @var Collection<int, Command>
     */
    #[ORM\OneToMany(targetEntity: Command::class, mappedBy: 'assignee')]
    private Collection $assigned_commands;

    public function __construct()
    {
        $this->commands = new ArrayCollection();
        $this->assigned_commands = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): static
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): static
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * @return Collection<int, Command>
     */
    public function getCommands(): Collection
    {
        return $this->commands;
    }

    public function addCommand(Command $command): static
    {
        if (!$this->commands->contains($command)) {
            $this->commands->add($command);
            $command->setAuthor($this);
        }

        return $this;
    }

    public function removeCommand(Command $command): static
    {
        if ($this->commands->removeElement($command)) {
            // set the owning side to null (unless already changed)
            if ($command->getAuthor() === $this) {
                $command->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Command>
     */
    public function getAssignedCommands(): Collection
    {
        return $this->assigned_commands;
    }

    public function addAssignedCommand(Command $assignedCommand): static
    {
        if (!$this->assigned_commands->contains($assignedCommand)) {
            $this->assigned_commands->add($assignedCommand);
            $assignedCommand->setAssignee($this);
        }

        return $this;
    }

    public function removeAssignedCommand(Command $assignedCommand): static
    {
        if ($this->assigned_commands->removeElement($assignedCommand)) {
            // set the owning side to null (unless already changed)
            if ($assignedCommand->getAssignee() === $this) {
                $assignedCommand->setAssignee(null);
            }
        }

        return $this;
    }
}
