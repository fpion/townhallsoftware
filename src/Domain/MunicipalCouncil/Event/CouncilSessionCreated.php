<?php

declare(strict_types=1);

namespace App\Domain\MunicipalCouncil\Event;

use App\Domain\MunicipalCouncil\ValueObject\CouncilSessionId;

final class CouncilSessionCreated implements DomainEvent
{
    public function __construct(
        public readonly CouncilSessionId $sessionId,
        public readonly string $townHallCode,
        public readonly \DateTimeImmutable $sessionDate,
        public readonly string $orderOfBusiness,
        private readonly \DateTimeImmutable $occurredAt,
    ) {}

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }
}
