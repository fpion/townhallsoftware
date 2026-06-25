<?php

declare(strict_types=1);

namespace App\Infrastructure\MunicipalCouncil\Persistence;

use App\Domain\MunicipalCouncil\Entity\Councilor;
use App\Domain\MunicipalCouncil\Repository\CouncilorRepositoryInterface;
use App\Domain\MunicipalCouncil\ValueObject\CouncilorId;

final class InMemoryCouncilorRepository implements CouncilorRepositoryInterface
{
    /** @var array<string, Councilor> */
    private array $store = [];

    public function save(Councilor $councilor): void
    {
        $this->store[$councilor->getId()->getValue()] = $councilor;
    }

    public function findById(CouncilorId $id): ?Councilor
    {
        return $this->store[$id->getValue()] ?? null;
    }

    public function findAll(): array
    {
        return array_values($this->store);
    }

    public function findAllActive(): array
    {
        return array_values(
            array_filter($this->store, fn(Councilor $c) => $c->isActive())
        );
    }

    public function countActive(): int
    {
        return count($this->findAllActive());
    }
}
