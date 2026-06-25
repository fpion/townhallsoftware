<?php

declare(strict_types=1);

namespace App\Controller\TownHall;

use App\Application\TownHall\Query\ListTownHalls\ListTownHallsHandler;
use App\Application\TownHall\Query\ListTownHalls\TownHallView;
use App\Controller\MunicipalCouncil\ApiExceptionHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/town-halls', name: 'api_town_hall_list', methods: ['GET'])]
final class ListTownHallsController
{
    use ApiExceptionHandler;

    public function __construct(
        private readonly ListTownHallsHandler $handler,
    ) {}

    public function __invoke(): JsonResponse
    {
        try {
            $views = $this->handler->handle();

            return $this->json(array_map(
                fn(TownHallView $v) => [
                    'code'         => $v->code,
                    'name'         => $v->name,
                    'street'       => $v->street,
                    'city'         => $v->city,
                    'postalCode'   => $v->postalCode,
                    'population'   => $v->population,
                    'maxCouncilors' => $v->maxCouncilors,
                    'maxAdjoints'  => $v->maxAdjoints,
                ],
                $views,
            ));
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
}
