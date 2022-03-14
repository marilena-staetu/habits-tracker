<?php

namespace App\EventListener;

use App\Entity\Event;
use App\Message\EventNotification;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Messenger\MessageBusInterface;

class EventPersistSubscriber implements EventSubscriber
{
    private MessageBusInterface $bus;

    /**
     * @param MessageBusInterface $bus
     */
    public function __construct(
        MessageBusInterface $bus,
    ) {
        $this->bus = $bus;
    }


    public function getSubscribedEvents()
    {
        return [
            Events::postPersist
        ];
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->dispatchMessage($args);
    }

    public function dispatchMessage(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof Event) {
            $id = $entity->getId();
            $this->bus->dispatch(new EventNotification($id));
        }
    }
}