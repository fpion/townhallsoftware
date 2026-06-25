<?php

declare(strict_types=1);

namespace App\Controller\TownHall;

use App\Application\TownHall\Command\CreateTownHall\CreateTownHallCommand;
use App\Application\TownHall\Command\CreateTownHall\CreateTownHallHandler;
use App\Controller\MunicipalCouncil\ApiExceptionHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * POST /api/town-halls
 *
 * Body JSON :
 *   - code       : string  (code INSEE, ex: "75056")
 *   - name       : string  (nom de la mairie)
 *   - street     : string
 *   - city       : string
 *   - postalCode : string
 *   - population : int
 */
#[Route('/api/createTownHall', name: 'api_town_hall_create', methods: ['POST'])]
final class CreateTownHallController
{
    use ApiExceptionHandler;

    public function __construct(
        private readonly CreateTownHallHandler $handler,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $body = $this->parseBody($request);

            $this->handler->handle(new CreateTownHallCommand(
                code: (string) $this->require($body, 'code'),
                name: (string) $this->require($body, 'name'),
                street: (string) $this->require($body, 'street'),
                city: (string) $this->require($body, 'city'),
                postalCode: (string) $this->require($body, 'postalCode'),
                population: (int) $this->require($body, 'population'),
            ));

            return $this->json(['message' => 'Mairie créée avec succès.'], 201);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
}
