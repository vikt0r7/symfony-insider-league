<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Infrastructure\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team
{
    public $goalDifference;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100)]
    private string $name;

    #[ORM\Column(type: 'integer')]
    private int $played = 0;

    #[ORM\Column(type: 'integer')]
    private int $won = 0;

    #[ORM\Column(type: 'integer')]
    private int $drawn = 0;

    #[ORM\Column(type: 'integer')]
    private int $lost = 0;

    #[ORM\Column(type: 'integer')]
    private int $goalsFor = 0;

    #[ORM\Column(type: 'integer')]
    private int $goalsAgainst = 0;

    #[ORM\Column(type: 'integer')]
    private int $points = 0;

    #[ORM\OneToMany(targetEntity: Player::class, mappedBy: 'team', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $players;

    #[ORM\OneToMany(targetEntity: MatchGame::class, mappedBy: 'homeTeam')]
    private Collection $homeMatches;

    #[ORM\OneToMany(targetEntity: MatchGame::class, mappedBy: 'awayTeam')]
    private Collection $awayMatches;

    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->homeMatches = new ArrayCollection();
        $this->awayMatches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function resetStats(): void
    {
        $this->played = $this->won = $this->drawn = $this->lost = 0;
        $this->goalsFor = $this->goalsAgainst = $this->points = 0;
    }

    public function getPlayed(): int
    {
        return $this->played;
    }

    public function addPlayed(): void
    {
        ++$this->played;
    }

    public function getWon(): int
    {
        return $this->won;
    }

    public function addWon(): void
    {
        ++$this->won;
        $this->addPoints(3);
    }

    public function getDrawn(): int
    {
        return $this->drawn;
    }

    public function addDrawn(): void
    {
        ++$this->drawn;
        $this->addPoints(1);
    }

    public function getLost(): int
    {
        return $this->lost;
    }

    public function addLost(): void
    {
        ++$this->lost;
    }

    public function getGoalsFor(): int
    {
        return $this->goalsFor;
    }

    public function addGoalsFor(int $goals): void
    {
        $this->goalsFor += $goals;
    }

    public function getGoalsAgainst(): int
    {
        return $this->goalsAgainst;
    }

    public function addGoalsAgainst(int $goals): void
    {
        $this->goalsAgainst += $goals;
    }

    public function getPoints(): int
    {
        return $this->points;
    }

    public function getGoalDifference(): int
    {
        return $this->goalsFor - $this->goalsAgainst;
    }

    /**
     * @return Collection<int, Player>
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): static
    {
        if (!$this->players->contains($player)) {
            $this->players->add($player);
            $player->setTeam($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): static
    {
        if ($this->players->removeElement($player) && $player->getTeam() === $this) {
            $player->setTeam(null);
        }

        return $this;
    }

    public function getRecentFormArray(): array
    {
        $matches = array_merge($this->getHomeMatches()->toArray(), $this->getAwayMatches()->toArray());

        usort($matches, fn ($a, $b) => $b->getWeek() <=> $a->getWeek());

        return array_slice(array_map(function (MatchGame $match) {
            $isHome = $match->getHomeTeam() === $this;
            $goalsFor = $isHome ? $match->getHomeScore() : $match->getAwayScore();
            $goalsAgainst = $isHome ? $match->getAwayScore() : $match->getHomeScore();

            return match (true) {
                $goalsFor > $goalsAgainst => 'W',
                $goalsFor === $goalsAgainst => 'D',
                default => 'L',
            };
        }, $matches), 0, 5);
    }

    /**
     * @return Collection<int, MatchGame>
     */
    public function getHomeMatches(): Collection
    {
        return $this->homeMatches;
    }

    /**
     * @return Collection<int, MatchGame>
     */
    public function getAwayMatches(): Collection
    {
        return $this->awayMatches;
    }

    public function applyMatchResult(int $goalsFor, int $goalsAgainst): void
    {
        ++$this->played;
        $this->goalsFor += $goalsFor;
        $this->goalsAgainst += $goalsAgainst;
        $this->goalDifference = $this->goalsFor - $this->goalsAgainst;

        if ($goalsFor > $goalsAgainst) {
            ++$this->won;
            $this->points += 3;
            $this->form[] = 'W';
        } elseif ($goalsFor < $goalsAgainst) {
            ++$this->lost;
            $this->form[] = 'L';
        } else {
            ++$this->drawn;
            ++$this->points;
            $this->form[] = 'D';
        }

        if (count($this->form) > 5) {
            array_shift($this->form);
        }
    }

    private function addPoints(int $pts): void
    {
        $this->points += $pts;
    }
}
