<?php

declare(strict_types=1);

namespace App\Domain\MunicipalCouncil\Event;

use App\Domain\MunicipalCouncil\ValueObject\CouncilSessionId;
use App\Domain\MunicipalCouncil\ValueObject\DeliberationId;

final class DeliberationAdded implements DomainEvent
{
    public function __construct(
        public readonly CouncilSessionId $sessionId,
        public readonly DeliberationId $deliberationId,
        public readonly string $number,
        public readonly string $title,
        private readonly \DateTimeImmutable $occurredAt,
    ) {}

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }
}
