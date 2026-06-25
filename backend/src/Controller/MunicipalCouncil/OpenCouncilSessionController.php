<?php

declare(strict_types=1);

namespace App\Controller\MunicipalCouncil;

use App\Application\MunicipalCouncil\Command\OpenCouncilSession\OpenCouncilSessionCommand;
use App\Application\MunicipalCouncil\Command\OpenCouncilSession\OpenCouncilSessionHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * POST /api/council-sessions/{id}/open
 *
 * Ouvre la séance après vérification du quorum et de l'envoi des convocations.
 * Aucun body requis.
 */
#[Route('/api/openCouncilSession/{id}', name: 'api_council_session_open', methods: ['POST'])]
final class OpenCouncilSessionController
{
    use ApiExceptionHandler;

    public function __construct(
        private readonly OpenCouncilSessionHandler $handler,
    ) {}

    public function __invoke(string $id, Request $request): JsonResponse
    {
        try {
            $this->handler->handle(new OpenCouncilSessionCommand($id));

            return $this->json(['message' => 'Séance ouverte.']);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
}
