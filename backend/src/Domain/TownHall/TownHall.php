<?php

declare(strict_types=1);

namespace App\Domain\TownHall;

class TownHall
{
    public function __construct(
        private readonly TownHallCode $code,
        private readonly string $name,
        private readonly Address $address,
        private readonly int $population,
    ) {
        if (trim($name) === '') {
            throw new \InvalidArgumentException('Le nom de la mairie ne peut pas être vide.');
        }
        if ($population < 0) {
            throw new \InvalidArgumentException('La population ne peut pas être négative.');
        }
    }

    public function getCode(): TownHallCode
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function getPopulation(): int
    {
        return $this->population;
    }
}
