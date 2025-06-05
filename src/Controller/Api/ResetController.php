<?php

namespace App\Controller\Api;

use App\Application\Command\ResetLeagueCommand;
use App\Application\Handler\ResetLeagueHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/reset', name: 'api_reset_league', methods: ['POST'])]
class ResetController extends AbstractController
{
    public function __invoke(ResetLeagueHandler $handler): JsonResponse
    {
        $handler->handle(new ResetLeagueCommand());

        return $this->json(['status' => 'League reset successfully']);
    }
}
