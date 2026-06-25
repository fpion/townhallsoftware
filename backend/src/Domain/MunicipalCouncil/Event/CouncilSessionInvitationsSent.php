<?php

declare(strict_types=1);

namespace App\Domain\MunicipalCouncil\Event;

use App\Domain\MunicipalCouncil\ValueObject\CouncilorId;
use App\Domain\MunicipalCouncil\ValueObject\CouncilSessionId;

/**
 * Événement émis lorsque les convocations sont expédiées aux conseillers municipaux
 * conformément à l'art. L2121-11 CGCT (délai minimum de 5 jours avant la séance).
 */
final class CouncilSessionInvitationsSent implements DomainEvent
{
    /**
     * @param CouncilorId[] $notifiedCouncilorIds   Identifiants des conseillers convoqués
     * @param string[]      $agendaItems            Intitulés des points inscrits à l'ordre du jour
     */
    public function __construct(
        public readonly CouncilSessionId $sessionId,
        public readonly \DateTimeImmutable $sessionDate,
        public readonly array $agendaItems,
        public readonly array $notifiedCouncilorIds,
        private readonly \DateTimeImmutable $occurredAt,
    ) {}

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }
}
