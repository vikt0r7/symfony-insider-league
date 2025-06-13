<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Api;

use App\Application\Command\ResetLeagueCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/reset', name: 'api_reset_league', methods: ['POST'])]
class ResetController extends AbstractController
{
    public function __construct(
        private MessageBusInterface $commandBus,
    ) {
    }

    public function __invoke(ResetLeagueCommand $command): JsonResponse
    {
        $this->commandBus->dispatch($command);

        return $this->json(['status' => 'League reset successfully']);
    }
}
