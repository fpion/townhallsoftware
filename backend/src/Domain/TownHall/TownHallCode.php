<?php

declare(strict_types=1);

namespace App\Domain\TownHall;

class TownHallCode
{
    public function __construct(
        private readonly string $code,
    ) {
        if (trim($code) === '') {
            throw new \InvalidArgumentException('Le code de la mairie ne peut pas être vide.');
        }
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function __toString(): string
    {
        return $this->code;
    }
}
