<?php

declare(strict_types=1);

namespace App\Application\MunicipalCouncil\Command\CreateCouncilSession;

use App\Domain\MunicipalCouncil\ValueObject\SessionType;

final class CreateCouncilSessionCommand
{
    public function __construct(
        public readonly string $townHallCode,
        public readonly \DateTimeImmutable $date,
        public readonly string $orderOfBusiness,
        public readonly SessionType $sessionType = SessionType::ORDINARY,
    ) {}
}
