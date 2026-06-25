<?php

declare(strict_types=1);

namespace App\Application\MunicipalCouncil\Command\VoteOnDeliberation;

final class VoteOnDeliberationCommand
{
    public function __construct(
        public readonly string $sessionId,
        public readonly string $deliberationId,
        public readonly int $pour,
        public readonly int $contre,
        public readonly int $abstention,
    ) {}
}
