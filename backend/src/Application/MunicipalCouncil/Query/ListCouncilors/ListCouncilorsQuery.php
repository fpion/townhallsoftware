<?php

declare(strict_types=1);

namespace App\Application\MunicipalCouncil\Query\ListCouncilors;

final class ListCouncilorsQuery
{
    public function __construct(
        public readonly string $townHallCode,
    ) {}
}
