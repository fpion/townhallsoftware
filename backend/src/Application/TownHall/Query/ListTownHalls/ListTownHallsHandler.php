<?php

declare(strict_types=1);

namespace App\Application\TownHall\Query\ListTownHalls;

use App\Domain\MunicipalCouncil\ValueObject\CouncilCompositionRule;
use App\Domain\TownHall\Repository\TownHallRepositoryInterface;
use App\Domain\TownHall\TownHall;

final class ListTownHallsHandler
{
    public function __construct(
        private readonly TownHallRepositoryInterface $townHallRepository,
    ) {}

    /** @return TownHallView[] */
    public function handle(): array
    {
        return array_map(
            fn(TownHall $t) => $this->toView($t),
            $this->townHallRepository->findAll(),
        );
    }

    private function toView(TownHall $townHall): TownHallView
    {
        $composition = CouncilCompositionRule::fromPopulation($townHall->getPopulation());

        return new TownHallView(
            code: $townHall->getCode()->getCode(),
            name: $townHall->getName(),
            street: $townHall->getAddress()->getStreet(),
            city: $townHall->getAddress()->getCity(),
            postalCode: $townHall->getAddress()->getPostalCode(),
            population: $townHall->getPopulation(),
            maxCouncilors: $composition->maxCouncilors(),
            maxAdjoints: $composition->maxAdjoints(),
        );
    }
}
