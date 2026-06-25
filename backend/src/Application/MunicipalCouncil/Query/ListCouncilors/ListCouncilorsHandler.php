<?php

declare(strict_types=1);

namespace App\Application\MunicipalCouncil\Query\ListCouncilors;

use App\Domain\MunicipalCouncil\Entity\Councilor;
use App\Domain\MunicipalCouncil\Repository\CouncilorRepositoryInterface;

final class ListCouncilorsHandler
{
    public function __construct(
        private readonly CouncilorRepositoryInterface $councilorRepository,
    ) {}

    /** @return CouncilorView[] */
    public function handle(): array
    {
        return array_map(
            fn(Councilor $c) => new CouncilorView(
                id: $c->getId()->getValue(),
                firstName: $c->getFirstName(),
                lastName: $c->getLastName(),
                email: $c->getEmail(),
                role: $c->getRole()->value,
                roleLabel: $c->getRole()->label(),
                active: $c->isActive(),
            ),
            $this->councilorRepository->findAll(),
        );
    }
}
