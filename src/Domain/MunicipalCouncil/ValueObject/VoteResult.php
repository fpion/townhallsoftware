<?php

declare(strict_types=1);

namespace App\Domain\MunicipalCouncil\ValueObject;

final class VoteResult
{
    public function __construct(
        private readonly int $pour,
        private readonly int $contre,
        private readonly int $abstention,
    ) {
        if ($pour < 0 || $contre < 0 || $abstention < 0) {
            throw new \InvalidArgumentException('Les votes ne peuvent pas être négatifs.');
        }
    }

    public function getPour(): int
    {
        return $this->pour;
    }

    public function getContre(): int
    {
        return $this->contre;
    }

    public function getAbstention(): int
    {
        return $this->abstention;
    }

    public function getTotalVotants(): int
    {
        return $this->pour + $this->contre + $this->abstention;
    }

    /**
     * Une délibération est adoptée si le nombre de votes "pour" est strictement
     * supérieur au nombre de votes "contre" (art. L2121-20 CGCT).
     * En cas d'égalité, la voix du maire est prépondérante.
     */
    public function isAdopted(): bool
    {
        return $this->pour > $this->contre;
    }

    public function equals(self $other): bool
    {
        return $this->pour === $other->pour
            && $this->contre === $other->contre
            && $this->abstention === $other->abstention;
    }
}
