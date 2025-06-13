<?php

declare(strict_types=1);

namespace App\Application\CommandHandler;

use App\Application\Command\SimulateWeekCommand;
use App\Application\Service\LeagueStateService;
use App\Application\Service\MatchSimulationService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;

class SimulateNextWeekHandler
{
    public function __construct(
        private readonly MatchSimulationService $simulation,
        private EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(SimulateWeekCommand $command): void
    {
        try {
            $this->simulation->simulateNextWeek();
            $this->entityManager->flush();
            $this->logger->info("Simulation completed successfully");
        } catch (Exception $e) {
            $this->logger->error("Error on simulation: {$e->getMessage()}");
        }
    }

}
