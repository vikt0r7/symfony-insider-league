<?php

namespace App\Controller\Api;

use App\Service\LeagueStateService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/league')]
class LeagueController extends AbstractController
{
    #[Route('/week', name: 'league_week', methods: ['GET'])]
    public function getCurrentWeek(LeagueStateService $stateService): JsonResponse
    {
        return $this->json(['week' => $stateService->getCurrentWeek()]);
    }
}
