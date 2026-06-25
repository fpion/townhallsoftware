<?php

declare(strict_types=1);

namespace App\Application\TownHall\Query\ListTownHalls;

final class TownHallView
{
    public function __construct(
        public readonly string $code,
        public readonly string $name,
        public readonly string $street,
        public readonly string $city,
        public readonly string $postalCode,
        public readonly int $population,
        public readonly int $maxCouncilors,
        public readonly int $maxAdjoints,
    ) {}
}
