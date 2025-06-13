<?php

declare(strict_types=1);

namespace App\Application\CommandHandler;

use App\Application\Command\ScheduleAllCommand;
use App\Application\Service\MatchSchedulerService;

class ScheduleAllHandler
{
    public function __construct(
        private MatchSchedulerService $scheduler,
    ) {
    }

    public function __invoke(ScheduleAllCommand $command): void
    {
        $this->scheduler->scheduleAllMatches();
    }
}
