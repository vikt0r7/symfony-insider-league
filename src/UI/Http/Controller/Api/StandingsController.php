<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Api;

use App\Application\Query\GetStandingsQuery;
use App\Application\QueryHandler\GetStandingsHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/standings')]
class StandingsController extends AbstractController
{
    public function __construct(private GetStandingsHandler $handler)
    {
    }

    #[Route('', name: 'api_standings', methods: ['GET'])]
    public function standings(GetStandingsQuery $query): JsonResponse
    {
        $standings = $this->handler->__invoke($query);

        return $this->json($standings);
    }
}
