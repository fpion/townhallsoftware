<?php

declare(strict_types=1);

namespace App\Controller\MunicipalCouncil;

use App\Domain\MunicipalCouncil\Exception\CouncilSessionNotFoundException;
use App\Domain\MunicipalCouncil\Exception\CouncilorNotFoundException;
use App\Domain\MunicipalCouncil\Exception\DeliberationNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;

trait ApiExceptionHandler
{
    private function handleException(\Throwable $e): JsonResponse
    {
        return match (true) {
            $e instanceof CouncilSessionNotFoundException,
            $e instanceof CouncilorNotFoundException,
            $e instanceof DeliberationNotFoundException => new JsonResponse(
                ['error' => $e->getMessage()], 404
            ),
            $e instanceof \InvalidArgumentException => new JsonResponse(
                ['error' => $e->getMessage()], 400
            ),
            $e instanceof \DomainException => new JsonResponse(
                ['error' => $e->getMessage()], 422
            ),
            default => new JsonResponse(
                ['error' => 'Erreur interne du serveur.'], 500
            ),
        };
    }

    private function json(mixed $data, int $status = 200): JsonResponse
    {
        return new JsonResponse($data, $status);
    }

    /** @return array<string, mixed> */
    private function parseBody(\Symfony\Component\HttpFoundation\Request $request): array
    {
        $body = json_decode($request->getContent(), true);

        if (!is_array($body)) {
            throw new \InvalidArgumentException('Le corps de la requête doit être un JSON valide.');
        }

        return $body;
    }

    private function require(array $body, string $field): mixed
    {
        if (!array_key_exists($field, $body) || $body[$field] === null || $body[$field] === '') {
            throw new \InvalidArgumentException(
                sprintf('Le champ "%s" est obligatoire.', $field)
            );
        }

        return $body[$field];
    }
}
