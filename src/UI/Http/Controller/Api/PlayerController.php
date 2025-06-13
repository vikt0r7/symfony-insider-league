<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Api;

use App\Application\QueryHandler\GetPlayersHandler;
use App\Query\GetPlayersQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/players')]
class PlayerController extends AbstractController
{
    public function __construct(
        private GetPlayersHandler $handler,
    ) {
    }

    #[Route('', name: 'player_index', methods: ['GET'])]
    public function index(GetPlayersQuery $query): JsonResponse
    {
        $players = $this->handler->__invoke($query);

        return $this->json($players);
    }
}
