<?php

declare(strict_types=1);

namespace App\Domain\MunicipalCouncil\Event;

use App\Domain\MunicipalCouncil\ValueObject\CouncilSessionId;
use App\Domain\MunicipalCouncil\ValueObject\DeliberationId;
use App\Domain\MunicipalCouncil\ValueObject\DeliberationStatus;
use App\Domain\MunicipalCouncil\ValueObject\VoteResult;

final class DeliberationVoted implements DomainEvent
{
    public function __construct(
        public readonly CouncilSessionId $sessionId,
        public readonly DeliberationId $deliberationId,
        public readonly VoteResult $voteResult,
        public readonly DeliberationStatus $outcome,
        private readonly \DateTimeImmutable $occurredAt,
    ) {}

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }
}
