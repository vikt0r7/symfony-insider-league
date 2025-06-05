<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Repository\MatchGameRepository;
use App\Service\SimulationService;
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
        private readonly SimulationService $simulator,
        private readonly MatchGameRepository $matchRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $week = 1;
        while (true) {
            $matches = $this->matchRepository->findUnplayedByWeek($week);
            if (empty($matches)) {
                break;
            }

            $output->writeln("<info>Simulating week {$week}</info>");
            $simulatedMatches = $this->simulator->simulateWeek($week);

            foreach ($simulatedMatches as $match) {
                $output->writeln(
                    sprintf(
                        '%s %d - %d %s',
                        $match->getHomeTeam()->getName(),
                        $match->getHomeScore(),
                        $match->getAwayScore(),
                        $match->getAwayTeam()->getName()
                    )
                );
            }

            ++$week;
        }

        $output->writeln('<comment>Simulation complete.</comment>');

        return Command::SUCCESS;
    }
}
