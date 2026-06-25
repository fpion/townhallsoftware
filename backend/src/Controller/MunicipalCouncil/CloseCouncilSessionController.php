<?php

declare(strict_types=1);

namespace App\Controller\MunicipalCouncil;

use App\Application\MunicipalCouncil\Command\CloseCouncilSession\CloseCouncilSessionCommand;
use App\Application\MunicipalCouncil\Command\CloseCouncilSession\CloseCouncilSessionHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * POST /api/council-sessions/{id}/close
 *
 * Clôture la séance. Les délibérations encore en attente sont retirées
 * de l'ordre du jour. Aucun body requis.
 */
#[Route('/api/council-sessions/{id}/close', name: 'api_council_session_close', methods: ['POST'])]
final class CloseCouncilSessionController
{
    use ApiExceptionHandler;

    public function __construct(
        private readonly CloseCouncilSessionHandler $handler,
    ) {}

    public function __invoke(string $id, Request $request): JsonResponse
    {
        try {
            $this->handler->handle(new CloseCouncilSessionCommand($id));

            return $this->json(['message' => 'Séance clôturée.']);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
}
