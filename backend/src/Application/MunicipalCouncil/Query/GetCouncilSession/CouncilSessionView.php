<?php

declare(strict_types=1);

namespace App\Application\MunicipalCouncil\Query\GetCouncilSession;

final class AttendanceView
{
    public function __construct(
        public readonly string $councilorId,
        public readonly string $status,
        public readonly string $statusLabel,
        public readonly ?string $proxyHolderId,
    ) {}
}

final class DeliberationView
{
    public function __construct(
        public readonly string $id,
        public readonly string $number,
        public readonly string $title,
        public readonly string $description,
        public readonly string $status,
        public readonly string $statusLabel,
        public readonly ?int $pour,
        public readonly ?int $contre,
        public readonly ?int $abstention,
    ) {}
}

final class CouncilSessionView
{
    /**
     * @param AttendanceView[]   $attendances
     * @param DeliberationView[] $deliberations
     */
    public function __construct(
        public readonly string $id,
        public readonly string $townHallCode,
        public readonly \DateTimeImmutable $date,
        public readonly string $orderOfBusiness,
        public readonly string $status,
        public readonly string $statusLabel,
        public readonly string $sessionType,
        public readonly bool $exceptional,
        public readonly int $presentCount,
        public readonly array $attendances,
        public readonly array $deliberations,
    ) {}
}
