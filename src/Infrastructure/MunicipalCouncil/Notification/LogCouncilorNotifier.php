<?php

declare(strict_types=1);

namespace App\Infrastructure\MunicipalCouncil\Notification;

use App\Application\MunicipalCouncil\Port\CouncilorNotifierInterface;
use App\Domain\MunicipalCouncil\Entity\Councilor;
use Psr\Log\LoggerInterface;

/**
 * Implémentation de démonstration : consigne les convocations dans les logs.
 * À remplacer par un adaptateur email (Symfony Mailer, Brevo, etc.) en production.
 */
final class LogCouncilorNotifier implements CouncilorNotifierInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {}

    public function sendSessionInvitation(
        \DateTimeImmutable $sessionDate,
        string $orderOfBusiness,
        array $agendaItems,
        array $councilors,
    ): void {
        foreach ($councilors as $councilor) {
            /** @var Councilor $councilor */
            $this->logger->info('Convocation expédiée', [
                'destinataire' => $councilor->getFullName(),
                'email'        => $councilor->getEmail(),
                'role'         => $councilor->getRole()->label(),
                'date_seance'  => $sessionDate->format('d/m/Y H:i'),
                'ordre_du_jour' => $orderOfBusiness,
                'points'        => $agendaItems,
            ]);
        }

        $this->logger->info(
            sprintf(
                'Convocations expédiées à %d conseiller(s) pour la séance du %s.',
                count($councilors),
                $sessionDate->format('d/m/Y'),
            )
        );
    }
}
