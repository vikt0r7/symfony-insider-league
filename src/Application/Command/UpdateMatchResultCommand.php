<?php

declare(strict_types=1);

namespace App\Dto;

use App\Shared\Attribute\RequestDto;
use Symfony\Component\Validator\Constraints as Assert;

#[RequestDto]
readonly class UpdateMatchResultCommand
{
    public function __construct(
        #[Assert\NotNull]
        public int $scoreA,
        #[Assert\NotNull]
        public int $scoreB,
    ) {
    }
}
