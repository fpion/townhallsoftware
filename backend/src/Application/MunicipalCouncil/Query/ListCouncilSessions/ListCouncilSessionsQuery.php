<?php

declare(strict_types=1);

namespace App\Application\MunicipalCouncil\Query\ListCouncilSessions;

final class ListCouncilSessionsQuery
{
    public function __construct(
        public readonly string $townHallCode,
    ) {}
}
