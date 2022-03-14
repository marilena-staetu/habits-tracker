<?php

namespace App\Service;

use App\Entity\Event;
use App\Entity\Habit;
use App\Repository\EventRepository;
use App\Repository\HabitRepository;
use Doctrine\ORM\EntityManagerInterface;

class EventService
{
    private EntityManagerInterface $em;

    private EventRepository $eventRepository;

    private HabitRepository $habitRepository;

    /**
     * @param EntityManagerInterface $em
     * @param HabitRepository $habitRepository
     * @param EventRepository $eventRepository
     */
    public function __construct(
        EntityManagerInterface $em,
        EventRepository $eventRepository,
        HabitRepository $habitRepository
    ) {

        $this->em = $em;
        $this->eventRepository = $eventRepository;
        $this->habitRepository = $habitRepository;
    }

    public function addEvent(string $habitId)
    {
        $event = new Event();

        $habit = $this->habitRepository->getHabitById($habitId);

        if (!($habit instanceof Habit)) {
            throw new \Exception('Habit not found!');
        }

        $event->setHabit($habit);

        $eventDate = date('Y-m-d');

        $sameEvent = $this->eventRepository->getEventByHabitAndDate($habitId, $eventDate);

        if ($sameEvent instanceof Event) {
            throw new \Exception('Event already exists!');
        }

        $this->em->persist($event);
        $this->em->flush();

        return $event->getId();

    }
}