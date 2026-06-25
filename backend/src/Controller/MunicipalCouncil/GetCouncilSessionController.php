<?php

declare(strict_types=1);

namespace App\Controller\MunicipalCouncil;

use App\Application\MunicipalCouncil\Query\GetCouncilSession\AttendanceView;
use App\Application\MunicipalCouncil\Query\GetCouncilSession\CouncilSessionView;
use App\Application\MunicipalCouncil\Query\GetCouncilSession\DeliberationView;
use App\Application\MunicipalCouncil\Query\GetCouncilSession\GetCouncilSessionHandler;
use App\Application\MunicipalCouncil\Query\GetCouncilSession\GetCouncilSessionQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * GET /api/council-sessions/{id}
 *
 * Retourne l'état complet d'une séance : statut, présences et délibérations.
 */
#[Route('/api/council-sessions/{id}', name: 'api_council_session_get', methods: ['GET'])]
final class GetCouncilSessionController
{
    use ApiExceptionHandler;

    public function __construct(
        private readonly GetCouncilSessionHandler $handler,
    ) {}

    public function __invoke(string $id, Request $request): JsonResponse
    {
        try {
            $view = $this->handler->handle(new GetCouncilSessionQuery($id));

            return $this->json($this->serialize($view));
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    private function serialize(CouncilSessionView $view): array
    {
        return [
            'id'              => $view->id,
            'townHallCode'    => $view->townHallCode,
            'date'            => $view->date->format(\DateTimeInterface::ATOM),
            'orderOfBusiness' => $view->orderOfBusiness,
            'status'          => $view->status,
            'statusLabel'     => $view->statusLabel,
            'sessionType'     => $view->sessionType,
            'exceptional'     => $view->exceptional,
            'presentCount'    => $view->presentCount,
            'attendances'     => array_map($this->serializeAttendance(...), $view->attendances),
            'deliberations'   => array_map($this->serializeDeliberation(...), $view->deliberations),
        ];
    }

    private function serializeAttendance(AttendanceView $a): array
    {
        return [
            'councilorId'   => $a->councilorId,
            'status'        => $a->status,
            'statusLabel'   => $a->statusLabel,
            'proxyHolderId' => $a->proxyHolderId,
        ];
    }

    private function serializeDeliberation(DeliberationView $d): array
    {
        return [
            'id'          => $d->id,
            'number'      => $d->number,
            'title'       => $d->title,
            'description' => $d->description,
            'status'      => $d->status,
            'statusLabel' => $d->statusLabel,
            'vote'        => $d->pour !== null ? [
                'pour'       => $d->pour,
                'contre'     => $d->contre,
                'abstention' => $d->abstention,
            ] : null,
        ];
    }
}
