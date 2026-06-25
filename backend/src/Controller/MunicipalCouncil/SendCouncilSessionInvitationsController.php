<?php

declare(strict_types=1);

namespace App\Controller\MunicipalCouncil;

use App\Application\MunicipalCouncil\Command\SendCouncilSessionInvitations\SendCouncilSessionInvitationsCommand;
use App\Application\MunicipalCouncil\Command\SendCouncilSessionInvitations\SendCouncilSessionInvitationsHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * POST /api/council-sessions/{id}/invitations
 *
 * Expédie les convocations à tous les conseillers municipaux actifs.
 * Doit être appelé au moins 5 jours avant la date de la séance (art. L2121-11 CGCT).
 * Aucun body requis.
 */
#[Route('/api/sendCouncilSessionInvitations/{id}', name: 'api_council_session_send_invitations', methods: ['POST'])]
final class SendCouncilSessionInvitationsController
{
    use ApiExceptionHandler;

    public function __construct(
        private readonly SendCouncilSessionInvitationsHandler $handler,
    ) {}

    public function __invoke(string $id, Request $request): JsonResponse
    {
        try {
            $this->handler->handle(new SendCouncilSessionInvitationsCommand($id));

            return $this->json(['message' => 'Convocations expédiées avec succès.']);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
}
