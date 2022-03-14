<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'user')]
#[ApiResource(
    collectionOperations: [
        'get' => ['security' => "is_granted('ROLE_USER')"],
        'post'
    ],
    itemOperations: [
        'get' => [
            'security' => "is_granted('ROLE_USER')"
        ],
        'put' => [
            'security' => "is_granted('ROLE_USER')"
        ],
        'delete' => [
            'security' => "is_granted('ROLE_ADMIN')"
        ],
        'patch' => [
            'security' => "is_granted('ROLE_USER')"
        ]
    ],
    denormalizationContext: ['groups' => ['user:write']],
    normalizationContext: ['groups' => ['user:read']]
)]
#[UniqueEntity(fields: 'email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['user:read'])]
    private int $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(["user:read", "user:write", "habit:read"])]
    #[Assert\NotBlank()]
    #[Assert\Email()]
    private string $email;

    #[ORM\Column(type: 'string')]
    #[Groups(["user:read", "user:write"])]
    private string $username;

    #[ORM\Column(type: 'json')]
    private iterable $roles = [];

    #[ORM\Column(type: 'string')]
    #[Groups(["user:write"])]
    private string $password;

    private string $plainPassword;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: 'Habit', orphanRemoval: true)]
    #[Groups(["user:read"])]
    private $habits;

    #[ORM\Column(type: 'boolean')]
    #[Groups(["user:read", "user:write"])]
    private bool $notification = false;

    public function __construct()
    {
        $this->habits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
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

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Habit>
     */
    public function getHabits(): Collection
    {
        return $this->habits;
    }

    public function addHabit(Habit $habit): self
    {
        if (!$this->habits->contains($habit)) {
            $this->habits[] = $habit;
            $habit->setOwner($this);
        }

        return $this;
    }

    public function removeHabit(Habit $habit): self
    {
        if ($this->habits->contains($habit)) {
            $this->habits->removeElement($habit);
            // set the owning side to null (unless already changed)
            if ($habit->getUser() === $this) {
                $habit->setUser(null);
            }
        }
        return $this;
    }

    public function getNotification(): ?bool
    {
        return $this->notification;
    }

    public function setNotification(bool $notification): self
    {
        $this->notification = $notification;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword(string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }
}
