<?php

declare(strict_types=1);

namespace App\Dto;

readonly class MatchDto
{
    public function __construct(
        public int $id,
        public ?int $scoreA,
        public ?int $scoreB,
        public TeamDto $teamA,
        public TeamDto $teamB,
        public int $week,
    ) {
    }
}
