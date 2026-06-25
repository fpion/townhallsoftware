<?php

declare(strict_types=1);

namespace App\Application\MunicipalCouncil\Query\ListCouncilSessions;

use App\Domain\MunicipalCouncil\Entity\CouncilSession;
use App\Domain\MunicipalCouncil\Repository\CouncilSessionRepositoryInterface;
use App\Domain\TownHall\Exception\TownHallNotFoundException;
use App\Domain\TownHall\Repository\TownHallRepositoryInterface;

final class ListCouncilSessionsHandler
{
    public function __construct(
        private readonly CouncilSessionRepositoryInterface $sessionRepository,
        private readonly TownHallRepositoryInterface $townHallRepository,
    ) {}

    /** @return CouncilSessionSummaryView[] */
    public function handle(ListCouncilSessionsQuery $query): array
    {
        $townHall = $this->townHallRepository->findByCode($query->townHallCode);

        if ($townHall === null) {
            throw new TownHallNotFoundException(
                sprintf('Mairie "%s" introuvable.', $query->townHallCode)
            );
        }

        $sessions = $this->sessionRepository->findByTownHallCode($query->townHallCode);

        usort($sessions, fn(CouncilSession $a, CouncilSession $b) => $b->getDate() <=> $a->getDate());

        return array_map(
            fn(CouncilSession $s) => new CouncilSessionSummaryView(
                id: $s->getId()->getValue(),
                townHallCode: $s->getTownHallCode(),
                date: $s->getDate(),
                status: $s->getStatus()->value,
                statusLabel: $s->getStatus()->label(),
                sessionType: $s->getType()->value,
                exceptional: $s->getType()->value === 'exceptional',
            ),
            $sessions,
        );
    }
}
