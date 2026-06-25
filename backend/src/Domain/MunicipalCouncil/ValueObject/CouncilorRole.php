<?php

declare(strict_types=1);

namespace App\Domain\MunicipalCouncil\ValueObject;

enum CouncilorRole: string
{
    case MAIRE = 'maire';
    case MAIRE_ADJOINT = 'maire_adjoint';
    case CONSEILLER_DELEGUE = 'conseiller_delegue';
    case CONSEILLER = 'conseiller';

    public function label(): string
    {
        return match($this) {
            self::MAIRE              => 'Maire',
            self::MAIRE_ADJOINT      => 'Maire adjoint',
            self::CONSEILLER_DELEGUE => 'Conseiller délégué',
            self::CONSEILLER         => 'Conseiller municipal',
        };
    }
}
