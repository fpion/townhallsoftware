<?php

declare(strict_types=1);

namespace App\Controller\MunicipalCouncil;

use App\Application\MunicipalCouncil\Command\AddDeliberation\AddDeliberationCommand;
use App\Application\MunicipalCouncil\Command\AddDeliberation\AddDeliberationHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * POST /api/council-sessions/{id}/deliberations
 *
 * Ajoute une délibération à une séance ouverte.
 *
 * Body JSON :
 *   - title       : string  (intitulé de la délibération)
 *   - description : string  (exposé des motifs)
 */
#[Route('/api/addDeliberation/{id}', name: 'api_council_session_add_deliberation', methods: ['POST'])]
final class AddDeliberationController
{
    use ApiExceptionHandler;

    public function __construct(
        private readonly AddDeliberationHandler $handler,
    ) {}

    public function __invoke(string $id, Request $request): JsonResponse
    {
        try {
            $body = $this->parseBody($request);

            $command = new AddDeliberationCommand(
                sessionId: $id,
                title: (string) $this->require($body, 'title'),
                description: (string) $this->require($body, 'description'),
            );

            $deliberationId = $this->handler->handle($command);

            return $this->json(['id' => $deliberationId->getValue()], 201);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
}
