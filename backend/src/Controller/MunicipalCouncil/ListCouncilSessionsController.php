<?php

declare(strict_types=1);

namespace App\Controller\MunicipalCouncil;

use App\Application\MunicipalCouncil\Query\ListCouncilSessions\CouncilSessionSummaryView;
use App\Application\MunicipalCouncil\Query\ListCouncilSessions\ListCouncilSessionsHandler;
use App\Application\MunicipalCouncil\Query\ListCouncilSessions\ListCouncilSessionsQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/listCouncilSessions', name: 'api_council_session_list', methods: ['GET'])]
final class ListCouncilSessionsController
{
    use ApiExceptionHandler;

    public function __construct(
        private readonly ListCouncilSessionsHandler $handler,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $townHallCode = $request->query->get('townHallCode', '');

            if (trim($townHallCode) === '') {
                return $this->json(['error' => 'Le paramètre townHallCode est requis.'], 400);
            }

            $views = $this->handler->handle(new ListCouncilSessionsQuery($townHallCode));

            return $this->json(array_map(
                fn(CouncilSessionSummaryView $v) => [
                    'id'          => $v->id,
                    'townHallCode' => $v->townHallCode,
                    'date'        => $v->date->format(\DateTimeInterface::ATOM),
                    'status'      => $v->status,
                    'statusLabel' => $v->statusLabel,
                    'sessionType' => $v->sessionType,
                    'exceptional' => $v->exceptional,
                ],
                $views,
            ));
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
}
