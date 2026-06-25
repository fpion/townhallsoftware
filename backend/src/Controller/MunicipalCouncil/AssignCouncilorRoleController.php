<?php

declare(strict_types=1);

namespace App\Controller\MunicipalCouncil;

use App\Application\MunicipalCouncil\Command\AssignCouncilorRole\AssignCouncilorRoleCommand;
use App\Application\MunicipalCouncil\Command\AssignCouncilorRole\AssignCouncilorRoleHandler;
use App\Domain\MunicipalCouncil\ValueObject\CouncilorRole;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * PATCH /api/councilors/{id}/role
 *
 * Body JSON :
 *   - townHallCode : string  (code INSEE de la mairie)
 *   - role         : string  (maire | maire_adjoint | conseiller_delegue | conseiller)
 */
#[Route('/api/councilors/{id}/role', name: 'api_councilor_assign_role', methods: ['PATCH'])]
final class AssignCouncilorRoleController
{
    use ApiExceptionHandler;

    public function __construct(
        private readonly AssignCouncilorRoleHandler $handler,
    ) {}

    public function __invoke(string $id, Request $request): JsonResponse
    {
        try {
            $body = $this->parseBody($request);

            $roleValue = (string) $this->require($body, 'role');
            $role = CouncilorRole::tryFrom($roleValue);

            if ($role === null) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'Rôle "%s" invalide. Valeurs acceptées : %s.',
                        $roleValue,
                        implode(', ', array_column(CouncilorRole::cases(), 'value')),
                    )
                );
            }

            $this->handler->handle(new AssignCouncilorRoleCommand(
                councilorId: $id,
                townHallCode: (string) $this->require($body, 'townHallCode'),
                newRole: $role,
            ));

            return $this->json(['message' => 'Rôle attribué avec succès.']);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
}
