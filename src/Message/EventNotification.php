<?php

namespace App\Message;

class EventNotification
{
    private $event;

    public function __construct(string $event)
    {
        $this->event = $event;
    }

    public function getContent(): string
    {
        return $this->event;
    }
}