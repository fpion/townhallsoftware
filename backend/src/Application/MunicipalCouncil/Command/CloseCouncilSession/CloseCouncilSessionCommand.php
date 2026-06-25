<?php

declare(strict_types=1);

namespace App\Application\MunicipalCouncil\Command\CloseCouncilSession;

final class CloseCouncilSessionCommand
{
    public function __construct(
        public readonly string $sessionId,
    ) {}
}
