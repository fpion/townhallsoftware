<?php

declare(strict_types=1);

namespace App\Application\MunicipalCouncil\Query\GetCouncilSession;

use App\Domain\MunicipalCouncil\Entity\CouncilSession;
use App\Domain\MunicipalCouncil\Entity\Deliberation;
use App\Domain\MunicipalCouncil\Exception\CouncilSessionNotFoundException;
use App\Domain\MunicipalCouncil\Repository\CouncilSessionRepositoryInterface;
use App\Domain\MunicipalCouncil\ValueObject\Attendance;
use App\Domain\MunicipalCouncil\ValueObject\CouncilSessionId;

final class GetCouncilSessionHandler
{
    public function __construct(
        private readonly CouncilSessionRepositoryInterface $sessionRepository,
    ) {}

    public function handle(GetCouncilSessionQuery $query): CouncilSessionView
    {
        $session = $this->sessionRepository->findById(
            CouncilSessionId::fromString($query->sessionId)
        );

        if ($session === null) {
            throw new CouncilSessionNotFoundException(
                sprintf('Séance "%s" introuvable.', $query->sessionId)
            );
        }

        return $this->toView($session);
    }

    private function toView(CouncilSession $session): CouncilSessionView
    {
        $attendanceViews = array_map(
            fn(Attendance $a) => new AttendanceView(
                councilorId: $a->getCouncilorId()->getValue(),
                status: $a->getStatus()->value,
                statusLabel: $a->getStatus()->label(),
                proxyHolderId: $a->getProxyHolderId()?->getValue(),
            ),
            $session->getAttendances(),
        );

        $deliberationViews = array_map(
            fn(Deliberation $d) => new DeliberationView(
                id: $d->getId()->getValue(),
                number: $d->getNumber(),
                title: $d->getTitle(),
                description: $d->getDescription(),
                status: $d->getStatus()->value,
                statusLabel: $d->getStatus()->label(),
                pour: $d->getVoteResult()?->getPour(),
                contre: $d->getVoteResult()?->getContre(),
                abstention: $d->getVoteResult()?->getAbstention(),
            ),
            $session->getDeliberations(),
        );

        return new CouncilSessionView(
            id: $session->getId()->getValue(),
            townHallCode: $session->getTownHallCode(),
            date: $session->getDate(),
            status: $session->getStatus()->value,
            statusLabel: $session->getStatus()->label(),
            sessionType: $session->getType()->value,
            exceptional: $session->getType()->value === 'exceptional',
            presentCount: $session->getPresentCount(),
            attendances: $attendanceViews,
            deliberations: $deliberationViews,
        );
    }
}
