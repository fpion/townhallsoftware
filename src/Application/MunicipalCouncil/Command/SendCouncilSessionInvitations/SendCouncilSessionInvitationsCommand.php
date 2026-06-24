<?php

declare(strict_types=1);

namespace App\Application\MunicipalCouncil\Command\SendCouncilSessionInvitations;

final class SendCouncilSessionInvitationsCommand
{
    public function __construct(
        public readonly string $sessionId,
    ) {}
}
