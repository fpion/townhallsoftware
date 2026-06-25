<?php

declare(strict_types=1);

namespace App\Domain\MunicipalCouncil\ValueObject;

enum AttendanceStatus: string
{
    case PRESENT = 'present';
    case ABSENT_EXCUSE = 'absent_excuse';
    case ABSENT = 'absent';
    case PROCURATION = 'procuration';

    public function label(): string
    {
        return match($this) {
            self::PRESENT => 'Présent',
            self::ABSENT_EXCUSE => 'Absent excusé',
            self::ABSENT => 'Absent',
            self::PROCURATION => 'Absent avec procuration',
        };
    }

    public function countsForQuorum(): bool
    {
        return $this === self::PRESENT;
    }
}
