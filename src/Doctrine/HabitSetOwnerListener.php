<?php

namespace App\Doctrine;

use App\Entity\Habit;
use Symfony\Component\Security\Core\Security;

class HabitSetOwnerListener
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist(Habit $habit)
    {
        if ($habit->getOwner()) {
            return;
        }

        if ($this->security->getUser())
        {
            $habit->setOwner($this->security->getUser());
        }
    }
}