<?php

declare(strict_types=1);

namespace App\Domain\MunicipalCouncil\Repository;

use App\Domain\MunicipalCouncil\Entity\CouncilSession;
use App\Domain\MunicipalCouncil\ValueObject\CouncilSessionId;

interface CouncilSessionRepositoryInterface
{
    public function save(CouncilSession $session): void;

    public function findById(CouncilSessionId $id): ?CouncilSession;

    /** @return CouncilSession[] */
    public function findByTownHallCode(string $townHallCode): array;
}
