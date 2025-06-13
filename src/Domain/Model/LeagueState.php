<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Infrastructure\Repository\LeagueStateRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LeagueStateRepository::class)]
class LeagueState
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(type: 'integer')]
    private int $currentWeek = 1;

    public function getCurrentWeek(): int
    {
        return $this->currentWeek;
    }

    public function incrementWeek(): void
    {
        ++$this->currentWeek;
    }

    public function reset(): void
    {
        $this->currentWeek = 1;
    }

    public function setCurrentWeek(int $week): void
    {
        $this->currentWeek = $week;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
