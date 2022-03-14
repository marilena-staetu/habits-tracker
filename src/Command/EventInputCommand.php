<?php

namespace App\Command;

use App\Service\EventService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EventInputCommand extends Command
{
    protected static $defaultName = 'app:event-input';

    protected static $defaultDescription = 'Add an event.';

    private EventService $eventService;
    /**
     * @param string|null $name The name of the command; passing null means it must be set in configure()
     *
     * @throws LogicException When the command name is empty
     */
    public function __construct(
        EventService $eventService,
        string $name = null
    ) {
        parent::__construct($name);
        $this->eventService = $eventService;
    }

    protected function configure(): void
    {
        $this->addArgument('habitId', InputArgument::REQUIRED, 'The parent habit id.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("Add an event.");

        $id = $this->eventService->addEvent($input->getArgument('habitId'));

        $output->writeln(sprintf('The event with id %s was inserted into the database', $id));

        return Command::SUCCESS;
    }
}