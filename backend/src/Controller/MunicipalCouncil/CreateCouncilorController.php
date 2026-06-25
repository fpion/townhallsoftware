<?php

declare(strict_types=1);

namespace App\Controller\MunicipalCouncil;

use App\Application\MunicipalCouncil\Command\CreateCouncilor\CreateCouncilorCommand;
use App\Application\MunicipalCouncil\Command\CreateCouncilor\CreateCouncilorHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * POST /api/councilors
 *
 * Body JSON :
 *   - firstName : string
 *   - lastName  : string
 *   - email     : string
 *
 * Le rôle par défaut est CONSEILLER. Utiliser PATCH /api/councilors/{id}/role pour changer.
 */
#[Route('/api/councilors', name: 'api_councilor_create', methods: ['POST'])]
final class CreateCouncilorController
{
    use ApiExceptionHandler;

    public function __construct(
        private readonly CreateCouncilorHandler $handler,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $body = $this->parseBody($request);

            $id = $this->handler->handle(new CreateCouncilorCommand(
                firstName: (string) $this->require($body, 'firstName'),
                lastName: (string) $this->require($body, 'lastName'),
                email: (string) $this->require($body, 'email'),
            ));

            return $this->json(['id' => $id->getValue()], 201);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
}
