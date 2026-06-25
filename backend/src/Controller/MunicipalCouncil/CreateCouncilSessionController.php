<?php

declare(strict_types=1);

namespace App\Controller\MunicipalCouncil;

use App\Application\MunicipalCouncil\Command\CreateCouncilSession\CreateCouncilSessionCommand;
use App\Application\MunicipalCouncil\Command\CreateCouncilSession\CreateCouncilSessionHandler;
use App\Domain\MunicipalCouncil\ValueObject\SessionType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * POST /api/council-sessions
 *
 * Body JSON :
 *   - townHallCode    : string  (code INSEE de la mairie)
 *   - date            : string  (ISO 8601, ex: "2026-09-15T18:00:00+02:00")
 *   - orderOfBusiness : string  (texte libre de l'ordre du jour)
 *   - exceptional     : bool    (optionnel, défaut false — 7 jours de délai si true, 15 jours sinon)
 */
#[Route('/api/council-sessions', name: 'api_council_session_create', methods: ['POST'])]
final class CreateCouncilSessionController
{
    use ApiExceptionHandler;

    public function __construct(
        private readonly CreateCouncilSessionHandler $handler,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $body = $this->parseBody($request);

            $isExceptional = filter_var($body['exceptional'] ?? false, FILTER_VALIDATE_BOOLEAN);

            $command = new CreateCouncilSessionCommand(
                townHallCode: (string) $this->require($body, 'townHallCode'),
                date: new \DateTimeImmutable((string) $this->require($body, 'date')),
                orderOfBusiness: (string) $this->require($body, 'orderOfBusiness'),
                sessionType: $isExceptional ? SessionType::EXCEPTIONAL : SessionType::ORDINARY,
            );

            $sessionId = $this->handler->handle($command);

            return $this->json(['id' => $sessionId->getValue()], 201);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
}
