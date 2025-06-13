<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Api;

use App\Application\Query\GetPredictionsQuery;
use App\Application\QueryHandler\GetPredictionsHandler;
use App\Domain\Exception\PredictionsNotAvailableException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class PredictionController extends AbstractController
{
    public function __construct(private GetPredictionsHandler $handler)
    {
    }

    #[Route('/predictions', methods: ['GET'])]
    public function getProbabilities(GetPredictionsQuery $query): JsonResponse
    {
        try {
            $data = $this->handler->__invoke($query);
        } catch (PredictionsNotAvailableException $e) {
            return $this->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($data);
    }
}
