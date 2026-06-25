<?php

declare(strict_types=1);

namespace App\Application\MunicipalCouncil\Command\CreateCouncilSession;

final class CreateCouncilSessionCommand
{
    public function __construct(
        public readonly string $townHallCode,
        public readonly \DateTimeImmutable $date,
        public readonly string $orderOfBusiness,
    ) {}
}
