<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\MatchGame;
use App\Entity\Team;
use App\Repository\MatchGameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/matches')]
class MatchController extends AbstractController
{
    #[Route('', name: 'api_matches_index', methods: ['GET'])]
    public function index(MatchGameRepository $repo): JsonResponse
    {
        $matches = $repo->findAll();

        $data = array_map(static fn (MatchGame $match) => [
            'id' => $match->getId(),
            'scoreA' => $match->getHomeScore(),
            'scoreB' => $match->getAwayScore(),
            'teamA' => [
                'id' => $match->getHomeTeam()->getId(),
                'name' => $match->getHomeTeam()->getName(),
            ],
            'teamB' => [
                'id' => $match->getAwayTeam()->getId(),
                'name' => $match->getAwayTeam()->getName(),
            ],
            'week' => $match->getWeek(),
        ], $matches);

        return $this->json($data);
    }

    #[Route('/{id}', name: 'api_match_update', methods: ['PUT'])]
    public function update(MatchGame $match, Request $request, EntityManagerInterface $em): Response
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $teams = $em->getRepository(Team::class)->findAll();
        foreach ($teams as $team) {
            $team->resetStats();
        }

        $match->setHomeScore((int) $data['scoreA']);
        $match->setAwayScore((int) $data['scoreB']);

        $em->flush();

        return new Response(null, 204);
    }
}
