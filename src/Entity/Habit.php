<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\HabitRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HabitRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => [
            'security' => "is_granted('ROLE_USER')"
        ],
        'post' => [
            'security' => "is_granted('ROLE_USER')"
        ]
    ],
    itemOperations: [
        'get' => [
            'security' => "is_granted('ROLE_USER')"
        ],
        'put' => [
            'security' => "is_granted('ROLE_USER')"
        ],
        'delete' => [
            'security' => "is_granted('ROLE_USER')"
        ],
        'patch' => [
            'security' => "is_granted('ROLE_USER')"
        ]
    ],
    denormalizationContext: ['groups' => ['habit:write']],
    normalizationContext: ['groups' => ['habit:read']]
)]
class Habit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['habit:read'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank()]
    #[Groups(['habit:read', 'habit:write', 'event:read'])]
    private $name;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'habits')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank()]
    #[Groups(['habit:read', 'habit:write'])]
    private $owner;

    #[ORM\OneToMany(mappedBy: 'habit', targetEntity: Event::class, orphanRemoval: true)]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['habit:read'])]
    private $events;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setHabit($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getHabit() === $this) {
                $event->setHabit(null);
            }
        }

        return $this;
    }
}
