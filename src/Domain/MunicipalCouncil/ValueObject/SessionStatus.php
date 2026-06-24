<?php

declare(strict_types=1);

namespace App\Domain\MunicipalCouncil\ValueObject;

enum SessionStatus: string
{
    case PLANNED = 'planned';
    case OPEN = 'open';
    case CLOSED = 'closed';

    public function label(): string
    {
        return match($this) {
            self::PLANNED => 'Planifiée',
            self::OPEN => 'En cours',
            self::CLOSED => 'Clôturée',
        };
    }
}
