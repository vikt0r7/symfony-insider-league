<?php

namespace App\Application\Handler;

use App\Application\Command\ScheduleAllCommand;
use App\Service\MatchSchedulerService;

class ScheduleAllHandler
{
    public function __construct(private MatchSchedulerService $scheduler)
    {
    }

    public function handle(ScheduleAllCommand $command): void
    {
        $this->scheduler->scheduleAllMatches();
    }
}
