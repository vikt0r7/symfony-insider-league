<?php

namespace App\Controller\Api;

use App\Application\Command\ScheduleAllCommand;
use App\Application\Command\SimulateAllCommand;
use App\Application\Command\SimulateWeekCommand;
use App\Application\Handler\ScheduleAllHandler;
use App\Application\Handler\SimulateAllHandler;
use App\Application\Handler\SimulateWeekHandler;
use App\Service\MatchSchedulerService;
use App\Service\LeagueStateService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/simulate')]
class SimulationController extends AbstractController
{
    #[Route('/play-all', name: 'simulate_play_all', methods: ['POST'])]
    public function playAll(SimulateAllHandler $handler): JsonResponse
    {
        $handler->handle(new SimulateAllCommand());
        return $this->json(['status' => 'All simulated via CQRS']);
    }


    #[Route('/schedule', name: 'simulate_schedule', methods: ['POST'])]
    public function schedule(ScheduleAllHandler $handler): JsonResponse
    {
        $handler->handle(new ScheduleAllCommand());
        return $this->json(['status' => 'Schedule created']);
    }

    #[Route('/next-week', name: 'simulate_next_week', methods: ['POST'])]
    public function nextWeek(SimulateWeekHandler $handler): JsonResponse
    {
        $handler->handle(
            new SimulateWeekCommand(0)
        );
        return $this->json(['status' => 'Next week simulated']);
    }
}
