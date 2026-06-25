<?php

declare(strict_types=1);

namespace App\Application\MunicipalCouncil\Port;

use App\Domain\MunicipalCouncil\Entity\Councilor;

/**
 * Port de sortie (driven port) chargé d'acheminer les convocations
 * vers chaque conseiller municipal.
 *
 * L'infrastructure fournit l'implémentation (email, courrier, SMS…).
 */
interface CouncilorNotifierInterface
{
    /**
     * @param Councilor[] $councilors   Destinataires de la convocation
     * @param string[]    $agendaItems  Points inscrits à l'ordre du jour
     */
    public function sendSessionInvitation(
        \DateTimeImmutable $sessionDate,
        string $orderOfBusiness,
        array $agendaItems,
        array $councilors,
    ): void;
}
