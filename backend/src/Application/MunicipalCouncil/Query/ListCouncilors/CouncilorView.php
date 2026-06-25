<?php

declare(strict_types=1);

namespace App\Application\MunicipalCouncil\Query\ListCouncilors;

final class CouncilorView
{
    public function __construct(
        public readonly string $id,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $email,
        public readonly string $role,
        public readonly string $roleLabel,
        public readonly bool $active,
        public readonly string $townHallCode,
    ) {}
}
