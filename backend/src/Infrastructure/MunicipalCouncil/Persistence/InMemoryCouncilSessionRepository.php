<?php

declare(strict_types=1);

namespace App\Infrastructure\MunicipalCouncil\Persistence;

use App\Domain\MunicipalCouncil\Entity\CouncilSession;
use App\Domain\MunicipalCouncil\Repository\CouncilSessionRepositoryInterface;
use App\Domain\MunicipalCouncil\ValueObject\CouncilSessionId;

final class InMemoryCouncilSessionRepository implements CouncilSessionRepositoryInterface
{
    /** @var array<string, CouncilSession> */
    private array $store = [];

    public function save(CouncilSession $session): void
    {
        $this->store[$session->getId()->getValue()] = $session;
    }

    public function findById(CouncilSessionId $id): ?CouncilSession
    {
        return $this->store[$id->getValue()] ?? null;
    }

    public function findByTownHallCode(string $townHallCode): array
    {
        return array_values(
            array_filter(
                $this->store,
                fn(CouncilSession $s) => $s->getTownHallCode() === $townHallCode
            )
        );
    }
}
