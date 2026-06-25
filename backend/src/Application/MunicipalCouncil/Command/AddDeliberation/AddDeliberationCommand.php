<?php

declare(strict_types=1);

namespace App\Application\MunicipalCouncil\Command\AddDeliberation;

final class AddDeliberationCommand
{
    public function __construct(
        public readonly string $sessionId,
        public readonly string $title,
        public readonly string $description,
    ) {}
}
