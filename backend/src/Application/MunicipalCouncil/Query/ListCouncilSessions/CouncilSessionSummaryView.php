<?php

declare(strict_types=1);

namespace App\Application\MunicipalCouncil\Query\ListCouncilSessions;

final class CouncilSessionSummaryView
{
    public function __construct(
        public readonly string $id,
        public readonly string $townHallCode,
        public readonly \DateTimeImmutable $date,
        public readonly string $status,
        public readonly string $statusLabel,
        public readonly string $sessionType,
        public readonly bool $exceptional,
    ) {}
}
