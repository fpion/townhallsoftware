<?php

declare(strict_types=1);

namespace App\Domain\MunicipalCouncil\Repository;

use App\Domain\MunicipalCouncil\Entity\Councilor;
use App\Domain\MunicipalCouncil\ValueObject\CouncilorId;

interface CouncilorRepositoryInterface
{
    public function save(Councilor $councilor): void;

    public function findById(CouncilorId $id): ?Councilor;

    /** @return Councilor[] */
    public function findAllActive(): array;

    public function countActive(): int;
}
