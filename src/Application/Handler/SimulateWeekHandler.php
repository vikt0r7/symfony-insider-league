<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\SimulateWeekCommand;
use App\Service\MatchSimulatorService;

class SimulateWeekHandler
{
    public function __construct(private readonly MatchSimulatorService $simulator)
    {
    }

    public function handle(SimulateWeekCommand $command): void
    {
        $this->simulator->simulateNextWeek();
    }
}
