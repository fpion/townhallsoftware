<?php

declare(strict_types=1);

namespace App\Application\MunicipalCouncil\Command\SendCouncilSessionInvitations;

use App\Application\MunicipalCouncil\Port\ClockInterface;
use App\Application\MunicipalCouncil\Port\CouncilorNotifierInterface;
use App\Domain\MunicipalCouncil\Exception\CouncilSessionNotFoundException;
use App\Domain\MunicipalCouncil\Repository\CouncilSessionRepositoryInterface;
use App\Domain\MunicipalCouncil\Repository\CouncilorRepositoryInterface;
use App\Domain\MunicipalCouncil\ValueObject\CouncilSessionId;

/**
 * Envoie les convocations à tous les conseillers municipaux actifs.
 *
 * Le délai légal (art. L2121-11 CGCT) est vérifié par l'agrégat :
 * - Conseil ordinaire    : au moins 15 jours avant la séance
 * - Conseil exceptionnel : au moins 7 jours avant la séance
 */
final class SendCouncilSessionInvitationsHandler
{
    public function __construct(
        private readonly CouncilSessionRepositoryInterface $sessionRepository,
        private readonly CouncilorRepositoryInterface $councilorRepository,
        private readonly CouncilorNotifierInterface $notifier,
        private readonly ClockInterface $clock,
    ) {}

    public function handle(SendCouncilSessionInvitationsCommand $command): void
    {
        $session = $this->sessionRepository->findById(
            CouncilSessionId::fromString($command->sessionId)
        );

        if ($session === null) {
            throw new CouncilSessionNotFoundException(
                sprintf('Séance "%s" introuvable.', $command->sessionId)
            );
        }

        $councilors = $this->councilorRepository->findAllActive();

        // Enregistre l'événement domain, vérifie le délai légal et protège contre les doubles envois
        $session->dispatchInvitations($councilors, $this->clock->now());

        // Achemine les convocations via le port de sortie (email, SMS, courrier…)
        $this->notifier->sendSessionInvitation(
            sessionDate: $session->getDate(),
            orderOfBusiness: $session->getOrderOfBusiness(),
            agendaItems: array_map(
                fn($d) => $d->getTitle(),
                $session->getDeliberations(),
            ),
            councilors: $councilors,
        );

        $this->sessionRepository->save($session);
    }
}
