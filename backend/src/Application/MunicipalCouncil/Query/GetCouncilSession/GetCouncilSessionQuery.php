<?php

declare(strict_types=1);

namespace App\Application\MunicipalCouncil\Query\GetCouncilSession;

final class GetCouncilSessionQuery
{
    public function __construct(
        public readonly string $sessionId,
    ) {}
}
