<?php

declare(strict_types=1);

namespace App\Application\TownHall\Command\CreateTownHall;

final class CreateTownHallCommand
{
    public function __construct(
        public readonly string $code,
        public readonly string $name,
        public readonly string $street,
        public readonly string $city,
        public readonly string $postalCode,
        public readonly int $population,
    ) {}
}
