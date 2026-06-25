<?php

declare(strict_types=1);

namespace App\Domain\MunicipalCouncil\ValueObject;

/**
 * Type de séance du conseil municipal.
 *
 * Détermine le délai légal de convocation (art. L2121-11 CGCT) :
 * - Ordinaire  : 15 jours avant la séance
 * - Exceptionnel : 7 jours avant la séance
 */
enum SessionType: string
{
    case ORDINARY = 'ordinary';
    case EXCEPTIONAL = 'exceptional';

    public function requiredNoticeDays(): int
    {
        return match($this) {
            self::ORDINARY    => 15,
            self::EXCEPTIONAL => 7,
        };
    }
}
