<?php

declare(strict_types=1);

namespace App\Application\QueryHandler;

use App\Infrastructure\Repository\PlayerRepository;
use App\Query\GetPlayersQuery;

class GetPlayersHandler
{
    public function __construct(private PlayerRepository $playerRepository)
    {
    }

    public function __invoke(GetPlayersQuery $query): array
    {
        return $this->playerRepository->findAll();
    }
}
