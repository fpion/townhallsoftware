<?php

declare(strict_types=1);

namespace App\Domain\MunicipalCouncil\Event;

use App\Domain\MunicipalCouncil\ValueObject\CouncilSessionId;
use App\Domain\MunicipalCouncil\ValueObject\SessionType;

final class CouncilSessionCreated implements DomainEvent
{
    public function __construct(
        public readonly CouncilSessionId $sessionId,
        public readonly string $townHallCode,
        public readonly \DateTimeImmutable $sessionDate,
        public readonly string $orderOfBusiness,
        public readonly SessionType $sessionType,
        private readonly \DateTimeImmutable $occurredAt,
    ) {}

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }
}
