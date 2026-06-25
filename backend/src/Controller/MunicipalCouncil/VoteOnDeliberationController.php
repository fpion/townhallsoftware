<?php

declare(strict_types=1);

namespace App\Controller\MunicipalCouncil;

use App\Application\MunicipalCouncil\Command\VoteOnDeliberation\VoteOnDeliberationCommand;
use App\Application\MunicipalCouncil\Command\VoteOnDeliberation\VoteOnDeliberationHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * POST /api/council-sessions/{id}/deliberations/{deliberationId}/vote
 *
 * Soumet le résultat du vote pour une délibération.
 *
 * Body JSON :
 *   - pour        : int  (nombre de votes pour)
 *   - contre      : int  (nombre de votes contre)
 *   - abstention  : int  (nombre d'abstentions)
 */
#[Route(
    '/api/voteOnDeliberation/{id}/{deliberationId}',
    name: 'api_council_session_vote_deliberation',
    methods: ['POST'],
)]
final class VoteOnDeliberationController
{
    use ApiExceptionHandler;

    public function __construct(
        private readonly VoteOnDeliberationHandler $handler,
    ) {}

    public function __invoke(string $id, string $deliberationId, Request $request): JsonResponse
    {
        try {
            $body = $this->parseBody($request);

            $command = new VoteOnDeliberationCommand(
                sessionId: $id,
                deliberationId: $deliberationId,
                pour: (int) $this->require($body, 'pour'),
                contre: (int) $this->require($body, 'contre'),
                abstention: (int) $this->require($body, 'abstention'),
            );

            $this->handler->handle($command);

            return $this->json(['message' => 'Vote enregistré.']);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
}
