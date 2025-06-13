<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Api;

use App\Application\Query\GetCurrentWeekQuery;
use App\Application\QueryHandler\GetCurrentWeekHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/league')]
class LeagueController extends AbstractController
{
    public function __construct(private GetCurrentWeekHandler $handler)
    {
    }

    #[Route('/week', name: 'league_week', methods: ['GET'])]
    public function getCurrentWeek(GetCurrentWeekQuery $query): JsonResponse
    {
        $week = $this->handler->__invoke($query);

        return $this->json(['week' => $week]);
    }
}
