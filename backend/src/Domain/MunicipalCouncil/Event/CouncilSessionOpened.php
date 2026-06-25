<?php

declare(strict_types=1);

namespace App\Domain\MunicipalCouncil\Event;

use App\Domain\MunicipalCouncil\ValueObject\CouncilSessionId;

final class CouncilSessionOpened implements DomainEvent
{
    public function __construct(
        public readonly CouncilSessionId $sessionId,
        public readonly \DateTimeImmutable $sessionDate,
        public readonly int $presentCount,
        private readonly \DateTimeImmutable $occurredAt,
    ) {}

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }
}
