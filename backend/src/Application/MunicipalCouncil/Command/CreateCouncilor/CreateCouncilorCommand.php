<?php

declare(strict_types=1);

namespace App\Application\MunicipalCouncil\Command\CreateCouncilor;

final class CreateCouncilorCommand
{
    public function __construct(
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $email,
    ) {}
}
