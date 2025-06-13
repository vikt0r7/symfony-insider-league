<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Infrastructure\Repository\MatchGameRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MatchGameRepository::class)]
class MatchGame
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $homeScore = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $awayScore = null;

    #[ORM\Column(type: 'integer')]
    private int $week;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: Team::class, inversedBy: 'homeMatches')]
        #[ORM\JoinColumn(nullable: false)]
        private ?Team $homeTeam,
        #[ORM\ManyToOne(targetEntity: Team::class, inversedBy: 'awayMatches')]
        #[ORM\JoinColumn(nullable: false)]
        private ?Team $awayTeam,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHomeTeam(): Team
    {
        return $this->homeTeam;
    }

    public function setHomeTeam(Team $team): self
    {
        $this->homeTeam = $team;

        return $this;
    }

    public function getAwayTeam(): Team
    {
        return $this->awayTeam;
    }

    public function setAwayTeam(Team $team): self
    {
        $this->awayTeam = $team;

        return $this;
    }

    public function getHomeScore(): ?int
    {
        return $this->homeScore;
    }

    public function setHomeScore(?int $score): self
    {
        $this->homeScore = $score;

        return $this;
    }

    public function getAwayScore(): ?int
    {
        return $this->awayScore;
    }

    public function setAwayScore(?int $score): self
    {
        $this->awayScore = $score;

        return $this;
    }

    public function getWeek(): int
    {
        return $this->week;
    }

    public function setWeek(int $week): self
    {
        $this->week = $week;

        return $this;
    }

    public function isPlayed(): bool
    {
        return null !== $this->homeScore && null !== $this->awayScore;
    }

    public function simulate(): void
    {
        if (null !== $this->homeScore && null !== $this->awayScore) {
            return;
        }

        $this->homeScore = random_int(0, 5);
        $this->awayScore = random_int(0, 5);
    }
}
