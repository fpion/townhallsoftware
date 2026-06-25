<?php

declare(strict_types=1);

namespace App\Controller\MunicipalCouncil;

use App\Application\MunicipalCouncil\Query\ListCouncilors\CouncilorView;
use App\Application\MunicipalCouncil\Query\ListCouncilors\ListCouncilorsHandler;
use App\Application\MunicipalCouncil\Query\ListCouncilors\ListCouncilorsQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/listCouncilors', name: 'api_councilor_list', methods: ['GET'])]
final class ListCouncilorsController
{
    use ApiExceptionHandler;

    public function __construct(
        private readonly ListCouncilorsHandler $handler,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $townHallCode = $request->query->get('townHallCode', '');

            if (trim($townHallCode) === '') {
                return $this->json(['error' => 'Le paramètre townHallCode est requis.'], 400);
            }

            $views = $this->handler->handle(new ListCouncilorsQuery($townHallCode));

            return $this->json(array_map(
                fn(CouncilorView $v) => [
                    'id'           => $v->id,
                    'firstName'    => $v->firstName,
                    'lastName'     => $v->lastName,
                    'email'        => $v->email,
                    'role'         => $v->role,
                    'roleLabel'    => $v->roleLabel,
                    'active'       => $v->active,
                    'townHallCode' => $v->townHallCode,
                ],
                $views,
            ));
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
}
