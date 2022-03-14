<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddEvent extends AbstractController
{
    private EventRepository $eventRepository;

    public function __construct(
        EventRepository $eventRepository
    )
    {
        $this->eventRepository = $eventRepository;
    }

    public function __invoke(Event $data): Event
    {
        $habitId = $data->getHabit()->getId();
        $eventDate = date('Y-m-d');

        $sameEvent = $this->eventRepository->getEventByHabitAndDate($habitId, $eventDate);

        if ($sameEvent instanceof Event) {
            throw new \Exception('Event already exists!');
        }
        return $data;
    }

}