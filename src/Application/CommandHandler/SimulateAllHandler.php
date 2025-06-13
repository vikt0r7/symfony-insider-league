<?php

declare(strict_types=1);

namespace App\Application\CommandHandler;

use App\Application\Command\SimulateAllCommand;
use App\Application\Service\MatchSimulationService;

readonly class SimulateAllHandler
{
    public function __construct(
        private MatchSimulationService $simulator,
    ) {
    }

    public function __invoke(SimulateAllCommand $command): void
    {
        $this->simulator->simulateAllWeeks();
    }
}
