<?php

namespace App\Application\Handler;

use App\Application\Command\SimulateAllCommand;
use App\Service\MatchSimulatorService;

class SimulateAllHandler
{
    public function __construct(
        private readonly MatchSimulatorService $simulator,
    ) {
    }

    public function handle(SimulateAllCommand $command): void
    {
        $this->simulator->simulateAll();
    }
}
