<?php

declare(strict_types=1);

namespace App\Domain\MunicipalCouncil\ValueObject;

/**
 * Règles de composition d'un conseil municipal selon la population de la commune.
 *
 * Barème légal : art. L2121-2 CGCT
 * Plafond des adjoints : floor(nbConseillers × 0,30) — art. L2122-2 CGCT
 */
final class CouncilCompositionRule
{
    private function __construct(
        private readonly int $maxCouncilors,
    ) {}

    public static function fromPopulation(int $population): self
    {
        return new self(match(true) {
            $population < 100    => 7,
            $population < 500    => 11,
            $population < 1_500  => 15,
            $population < 2_500  => 19,
            $population < 3_500  => 23,
            $population < 5_000  => 27,
            $population < 10_000 => 29,
            $population < 20_000 => 33,
            $population < 30_000 => 35,
            $population < 40_000 => 39,
            $population < 50_000 => 43,
            $population < 60_000 => 45,
            $population < 80_000 => 49,
            $population < 100_000 => 53,
            $population < 150_000 => 55,
            $population < 200_000 => 59,
            $population < 250_000 => 61,
            $population < 300_000 => 65,
            default               => 69,
        });
    }

    public function maxCouncilors(): int
    {
        return $this->maxCouncilors;
    }

    /** Nombre maximum d'adjoints au maire (art. L2122-2 CGCT). */
    public function maxAdjoints(): int
    {
        return (int) floor($this->maxCouncilors * 0.30);
    }
}
