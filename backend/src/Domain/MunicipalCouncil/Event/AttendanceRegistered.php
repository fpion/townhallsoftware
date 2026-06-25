<?php

declare(strict_types=1);

namespace App\Domain\MunicipalCouncil\Event;

use App\Domain\MunicipalCouncil\ValueObject\AttendanceStatus;
use App\Domain\MunicipalCouncil\ValueObject\CouncilorId;
use App\Domain\MunicipalCouncil\ValueObject\CouncilSessionId;

final class AttendanceRegistered implements DomainEvent
{
    public function __construct(
        public readonly CouncilSessionId $sessionId,
        public readonly CouncilorId $councilorId,
        public readonly AttendanceStatus $status,
        private readonly \DateTimeImmutable $occurredAt,
    ) {}

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }
}
