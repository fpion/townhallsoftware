<?php

declare(strict_types=1);

namespace App\Application\MunicipalCouncil\Command\RegisterAttendance;

use App\Domain\MunicipalCouncil\ValueObject\AttendanceStatus;

final class RegisterAttendanceCommand
{
    public function __construct(
        public readonly string $sessionId,
        public readonly string $councilorId,
        public readonly AttendanceStatus $status,
        /** Identifiant du conseiller porteur de la procuration (si statut = PROCURATION) */
        public readonly ?string $proxyHolderId = null,
    ) {}
}
