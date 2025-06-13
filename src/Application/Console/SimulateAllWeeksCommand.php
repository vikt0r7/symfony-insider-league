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
    name: 'app:simulate:all-weeks',
    description: 'Simulate all match weeks'
)]
class SimulateAllWeeksCommand extends Command
{
    public function __construct(
        private readonly MatchSimulationService $simulator,
        private readonly EntityManagerInterface $entityManager,
        private readonly int $maxWeeks = 6,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $allSimulatedMatches = $this->simulator->simulateAllWeeks();

        $this->entityManager->flush();

        $week = 1;
        foreach ($allSimulatedMatches as $match) {
            $output->writeln(
                sprintf(
                    '%s %d - %d %s (Week %d)',
                    $match->getHomeTeam()->getName(),
                    $match->getHomeScore(),
                    $match->getAwayScore(),
                    $match->getAwayTeam()->getName(),
                    $match->getWeek()
                )
            );
        }

        $output->writeln('<comment>Simulation complete.</comment>');

        return Command::SUCCESS;
    }
}
