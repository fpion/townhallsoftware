<?php

declare(strict_types=1);

namespace App\Domain\MunicipalCouncil\ValueObject;

enum DeliberationStatus: string
{
    case PENDING = 'pending';
    case ADOPTED = 'adopted';
    case REJECTED = 'rejected';
    case WITHDRAWN = 'withdrawn';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'En attente de vote',
            self::ADOPTED => 'Adoptée',
            self::REJECTED => 'Rejetée',
            self::WITHDRAWN => 'Retirée de l\'ordre du jour',
        };
    }
}
