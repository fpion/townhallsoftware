<?php

declare(strict_types=1);

namespace App\Application\MunicipalCouncil\Command\SendCouncilSessionInvitations;

use App\Application\MunicipalCouncil\Port\CouncilorNotifierInterface;
use App\Domain\MunicipalCouncil\Exception\CouncilSessionNotFoundException;
use App\Domain\MunicipalCouncil\Repository\CouncilSessionRepositoryInterface;
use App\Domain\MunicipalCouncil\Repository\CouncilorRepositoryInterface;
use App\Domain\MunicipalCouncil\ValueObject\CouncilSessionId;

/**
 * Envoie les convocations à tous les conseillers municipaux actifs.
 *
 * Conformément à l'art. L2121-11 CGCT, les convocations doivent parvenir
 * aux conseillers au moins 5 jours francs avant la date de la séance.
 * Cette contrainte de délai est de la responsabilité de l'appelant.
 */
final class SendCouncilSessionInvitationsHandler
{
    public function __construct(
        private readonly CouncilSessionRepositoryInterface $sessionRepository,
        private readonly CouncilorRepositoryInterface $councilorRepository,
        private readonly CouncilorNotifierInterface $notifier,
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

        // Enregistre l'événement domain et protège contre les doubles envois
        $session->dispatchInvitations($councilors);

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
