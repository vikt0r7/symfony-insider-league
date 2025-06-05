<?php

namespace App\Controller\Api;

use App\Service\LeagueStateService;
use App\Service\PredictionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class PredictionController extends AbstractController
{
    #[Route('/predictions', methods: ['GET'])]
    public function getProbabilities(
        LeagueStateService $stateService,
        PredictionService $predictionService
    ): JsonResponse {
        if ($stateService->getCurrentWeek() <= 4) {
            return $this->json(['message' => 'Predictions available after week 4'], 400);
        }

        $data = $predictionService->calculateProbabilities();
        return $this->json($data);
    }
}
