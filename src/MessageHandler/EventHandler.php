<?php

namespace App\MessageHandler;

use App\Entity\Event;
use App\Message\EventNotification;
use App\Repository\EventRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Email;

#[AsMessageHandler(fromTransport: 'async')]
class EventHandler implements MessageHandlerInterface
{
    private EventRepository $eventRepository;
    private MailerInterface $mailer;

    public function __construct(
        EventRepository $eventRepository,
        MailerInterface $mailer
    ) {;

        $this->eventRepository = $eventRepository;
        $this->mailer = $mailer;
    }

    public function __invoke(EventNotification $newEvent)
    {
        /** @var Event $event */
        $event = $this->eventRepository->getEventById($newEvent->getContent());
        $habitId = $event->getHabit()->getId();
        $events = $this->eventRepository->countEventsOfAHabit($habitId);

        $email = (new Email())
            ->text(sprintf('You inserted the %s event', $events));

        $this->mailer->send($email);
    }
}