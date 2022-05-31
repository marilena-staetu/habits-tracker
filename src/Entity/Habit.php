<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\HabitRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Validator\IsValidOwner;

#[ORM\Entity(repositoryClass: HabitRepository::class)]
#[ORM\EntityListeners(["App\Doctrine\HabitSetOwnerListener"])]
#[ApiResource(
    collectionOperations: [
        'get',
        'post'
    ],
    itemOperations: [
        'get',
        'put' => [
            'security' => "is_granted('EDIT', object)",
            'security_message' => "Only the creator can edit a habit.",
        ],
        'delete',
        'patch' => [
            'security' => "is_granted('EDIT', object)"
        ]
    ],
    attributes: ["security" => "is_granted('ROLE_USER')"],

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
    #[Groups(['habit:read', 'habit:write', "event:item:get"])]
    private $name;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'habits')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['habit:read', 'habit:collection:post'])]
    #[IsValidOwner()]
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
