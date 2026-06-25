<?php

declare(strict_types=1);

namespace App\Controller\MunicipalCouncil;

use App\Application\MunicipalCouncil\Query\ListCouncilors\CouncilorView;
use App\Application\MunicipalCouncil\Query\ListCouncilors\ListCouncilorsHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/councilors', name: 'api_councilor_list', methods: ['GET'])]
final class ListCouncilorsController
{
    use ApiExceptionHandler;

    public function __construct(
        private readonly ListCouncilorsHandler $handler,
    ) {}

    public function __invoke(): JsonResponse
    {
        try {
            $views = $this->handler->handle();

            return $this->json(array_map(
                fn(CouncilorView $v) => [
                    'id'        => $v->id,
                    'firstName' => $v->firstName,
                    'lastName'  => $v->lastName,
                    'email'     => $v->email,
                    'role'      => $v->role,
                    'roleLabel' => $v->roleLabel,
                    'active'    => $v->active,
                ],
                $views,
            ));
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
}
