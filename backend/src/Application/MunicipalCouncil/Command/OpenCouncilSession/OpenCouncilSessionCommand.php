<?php

declare(strict_types=1);

namespace App\Application\MunicipalCouncil\Command\OpenCouncilSession;

final class OpenCouncilSessionCommand
{
    public function __construct(
        public readonly string $sessionId,
    ) {}
}
