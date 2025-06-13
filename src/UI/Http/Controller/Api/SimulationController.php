<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Api;

use App\Application\Command\ScheduleAllCommand;
use App\Application\Command\SimulateAllCommand;
use App\Application\Command\SimulateWeekCommand;
use App\Application\Query\GetCurrentWeekQuery;
use App\Application\QueryHandler\GetCurrentWeekHandler;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/simulate')]
class SimulationController extends AbstractController
{
    public function __construct(
        private MessageBusInterface $commandBus,
        private LoggerInterface $logger,
    ) {
    }

    #[Route('/play-all', name: 'simulate_play_all', methods: ['POST'])]
    public function playAll(SimulateAllCommand $command): JsonResponse
    {
        $this->commandBus->dispatch($command);

        return $this->json(['status' => 'All simulated via CQRS']);
    }

    #[Route('/schedule', name: 'simulate_schedule', methods: ['POST'])]
    public function schedule(ScheduleAllCommand $command): JsonResponse
    {
        $this->commandBus->dispatch($command);

        return $this->json(['status' => 'Schedule created']);
    }

    #[Route('/next-week', name: 'simulate_next_week', methods: ['POST'])]
    public function nextWeek(): JsonResponse
    {
        $command = new SimulateWeekCommand();
        $this->commandBus->dispatch($command);

        $this->logger->info('Command SimulateWeekCommand dispatched');

        return $this->json(['status' => 'Next week simulated']);
    }
}
