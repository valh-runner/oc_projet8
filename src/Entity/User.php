<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Table('user')]
#[ORM\Entity]
#[UniqueEntity('email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    /**
     * @var ?int $id Identifier
     */
    #[ORM\Column()]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    /**
     * @var ?string $username Username
     */
    #[ORM\Column(length: 25, unique: true)]
    #[Assert\NotBlank(message: "Vous devez saisir un nom d'utilisateur.")]
    private ?string $username = null;

    /**
     * @var ?string $password User password
     */
    #[ORM\Column(length: 64)]
    private ?string $password = null;

    /**
     * @var ?string $email User email
     */
    #[ORM\Column(length: 60, unique: true)]
    #[Assert\NotBlank(message: 'Vous devez saisir une adresse email.')]
    #[Assert\Email(message: "Le format de l'adresse n'est pas correcte.")]
    private ?string $email = null;

    /**
     * @var Collection $tasks Tasks
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Task::class)]
    private Collection $tasks;

    /**
     * @var array $roles User roles
     */
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    /**
     * Id getter
     * 
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Username getter
     *
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * Username setter
     *
     * @param string $username
     * @return void
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * Password getter
     *
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Password setter
     * 
     * @param string $password
     * @return void
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * Email getter
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Email setter
     *
     * @param string $email
     * @return void
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * The public representation of the user (e.g. a username, an email address, etc.).
     *
     * @see UserInterface
     * @return string
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * Roles getter
     *
     * @return array
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER'; // Guarantee every user at least has ROLE_USER.

        return array_unique($roles);
    }

    /**
     * Roles setter
     *
     * @param array $roles
     * @return self
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     * @return string|null
     */
    public function getSalt(): ?string
    {
        return null;
    }


    /**
     * Credentials erase command
     *
     * @see UserInterface
     * @return void
     */
    public function eraseCredentials(): void
    {
    }


    /**
     * Tasks getter
     *
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    /**
     * Task add
     *
     * @param Task $task
     * @return static
     */
    public function addTask(Task $task): static
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setOwner($this);
        }

        return $this;
    }

    /**
     * Task remove
     *
     * @param Task $task
     * @return static
     */
    public function removeTask(Task $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // Set the owning side to null (unless already changed).
            if ($task->getOwner() === $this) {
                $task->setOwner(null);
            }
        }

        return $this;
    }
}
