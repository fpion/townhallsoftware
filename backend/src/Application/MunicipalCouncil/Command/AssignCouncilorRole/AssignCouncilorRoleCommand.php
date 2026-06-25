<?php

declare(strict_types=1);

namespace App\Application\MunicipalCouncil\Command\AssignCouncilorRole;

use App\Domain\MunicipalCouncil\ValueObject\CouncilorRole;

final class AssignCouncilorRoleCommand
{
    public function __construct(
        public readonly string $councilorId,
        public readonly string $townHallCode,
        public readonly CouncilorRole $newRole,
    ) {}
}
