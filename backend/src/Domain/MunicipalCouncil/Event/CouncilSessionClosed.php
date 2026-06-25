<?php

declare(strict_types=1);

namespace App\Domain\MunicipalCouncil\Event;

use App\Domain\MunicipalCouncil\ValueObject\CouncilSessionId;

final class CouncilSessionClosed implements DomainEvent
{
    public function __construct(
        public readonly CouncilSessionId $sessionId,
        private readonly \DateTimeImmutable $occurredAt,
    ) {}

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }
}
