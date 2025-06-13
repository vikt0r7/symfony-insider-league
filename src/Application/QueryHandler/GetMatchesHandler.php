<?php

declare(strict_types=1);

namespace App\Application\QueryHandler;

use App\Application\Query\GetMatchesQuery;
use App\Dto\MatchDto;
use App\Dto\TeamDto;
use App\Infrastructure\Repository\MatchGameRepository;

class GetMatchesHandler
{
    public function __construct(private MatchGameRepository $repo)
    {
    }

    /**
     * @return MatchDto[]
     */
    public function __invoke(GetMatchesQuery $query): array
    {
        $matches = $this->repo->findBy([], ['week' => 'ASC', 'id' => 'ASC']);

        return array_map(static fn($match) => new MatchDto(
            $match->getId(),
            $match->getHomeScore(),
            $match->getAwayScore(),
            new TeamDto($match->getHomeTeam()->getId(), $match->getHomeTeam()->getName()),
            new TeamDto($match->getAwayTeam()->getId(), $match->getAwayTeam()->getName()),
            $match->getWeek()
        ), $matches);
    }
}
