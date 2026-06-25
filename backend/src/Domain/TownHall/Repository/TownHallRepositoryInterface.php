<?php

declare(strict_types=1);

namespace App\Domain\TownHall\Repository;

use App\Domain\TownHall\TownHall;

interface TownHallRepositoryInterface
{
    public function save(TownHall $townHall): void;

    public function findByCode(string $code): ?TownHall;

    /** @return TownHall[] */
    public function findAll(): array;
}
