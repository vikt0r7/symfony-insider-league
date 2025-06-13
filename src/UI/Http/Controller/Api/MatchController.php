<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Api;

use App\Application\Query\GetMatchesQuery;
use App\Application\QueryHandler\GetMatchesHandler;
use App\Dto\UpdateMatchResultCommand;
use App\Infrastructure\Repository\MatchGameRepository;
use App\Application\Service\MatchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/matches')]
class MatchController extends AbstractController
{
    public function __construct(
        private MessageBusInterface $commandBus,
        private GetMatchesHandler $getMatchesHandler,
        private MatchService $matchService,
        private MatchGameRepository $matchRepository,
    ) {
    }

    #[Route('', name: 'api_matches_index', methods: ['GET'])]
    public function index(GetMatchesQuery $query): JsonResponse
    {
        $matches = $this->getMatchesHandler->__invoke($query);

        return $this->json($matches);
    }

    #[Route('/{id}', name: 'api_match_update', methods: ['PUT'])]
    public function update(int $id, UpdateMatchResultCommand $command): JsonResponse
    {
        $match = $this->matchRepository->find($id);
        if (!$match) {
            return new JsonResponse(['error' => 'Match not found'], 404);
        }

        $this->commandBus->dispatch($command);

        $this->matchService->resetMatchResult($match, $command->scoreA, $command->scoreB);

        return new JsonResponse(null, 204);
    }
}
