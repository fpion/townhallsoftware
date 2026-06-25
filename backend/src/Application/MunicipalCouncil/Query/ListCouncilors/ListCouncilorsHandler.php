<?php

declare(strict_types=1);

namespace App\Application\MunicipalCouncil\Query\ListCouncilors;

use App\Domain\MunicipalCouncil\Entity\Councilor;
use App\Domain\MunicipalCouncil\Repository\CouncilorRepositoryInterface;
use App\Domain\TownHall\Exception\TownHallNotFoundException;
use App\Domain\TownHall\Repository\TownHallRepositoryInterface;

final class ListCouncilorsHandler
{
    public function __construct(
        private readonly CouncilorRepositoryInterface $councilorRepository,
        private readonly TownHallRepositoryInterface $townHallRepository,
    ) {}

    /** @return CouncilorView[] */
    public function handle(ListCouncilorsQuery $query): array
    {
        if ($this->townHallRepository->findByCode($query->townHallCode) === null) {
            throw new TownHallNotFoundException(
                sprintf('Mairie "%s" introuvable.', $query->townHallCode)
            );
        }

        return array_map(
            fn(Councilor $c) => new CouncilorView(
                id: $c->getId()->getValue(),
                firstName: $c->getFirstName(),
                lastName: $c->getLastName(),
                email: $c->getEmail(),
                role: $c->getRole()->value,
                roleLabel: $c->getRole()->label(),
                active: $c->isActive(),
                townHallCode: $c->getTownHallCode(),
            ),
            $this->councilorRepository->findByTownHallCode($query->townHallCode),
        );
    }
}
