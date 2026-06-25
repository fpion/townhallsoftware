<?php

declare(strict_types=1);

namespace App\Application\MunicipalCouncil\Port;

interface ClockInterface
{
    public function now(): \DateTimeImmutable;
}
