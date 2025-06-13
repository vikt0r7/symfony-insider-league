<?php

declare(strict_types=1);

namespace App\Application\Console;

use App\Application\Service\MatchSimulationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:simulate:next-week',
    description: 'Simulate next week matches manually'
)]
class SimulateNextWeekCommand extends Command
{
    public function __construct(
        private MatchSimulationService $simulator,
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Starting next week simulation...');

        $this->simulator->simulateNextWeek();

        $this->entityManager->flush();

        $output->writeln('Next week simulation completed.');

        return Command::SUCCESS;
    }
}
