<?php

namespace App\Application\Handler;

use App\Application\Command\SimulateWeekCommand;
use App\Service\MatchSimulatorService;

class SimulateWeekHandler
{
    public function __construct(private MatchSimulatorService $simulator)
    {
    }

    public function handle(SimulateWeekCommand $command): void
    {
        $this->simulator->simulateNextWeek();
    }
}
