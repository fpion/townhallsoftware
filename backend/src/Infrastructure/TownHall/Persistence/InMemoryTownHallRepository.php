<?php

declare(strict_types=1);

namespace App\Infrastructure\TownHall\Persistence;

use App\Domain\TownHall\Repository\TownHallRepositoryInterface;
use App\Domain\TownHall\TownHall;

final class InMemoryTownHallRepository implements TownHallRepositoryInterface
{
    /** @var array<string, TownHall> */
    private array $store = [];

    public function save(TownHall $townHall): void
    {
        $this->store[$townHall->getCode()->getCode()] = $townHall;
    }

    public function findByCode(string $code): ?TownHall
    {
        return $this->store[$code] ?? null;
    }
}
