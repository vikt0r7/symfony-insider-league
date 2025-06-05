<?php

namespace App\Application\Command;

class SimulateWeekCommand
{
    public function __construct(public int $week)
    {
    }
}
