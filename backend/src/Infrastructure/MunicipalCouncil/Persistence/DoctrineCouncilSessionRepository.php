<?php

declare(strict_types=1);

namespace App\Infrastructure\MunicipalCouncil\Persistence;

use App\Domain\MunicipalCouncil\Entity\CouncilSession;
use App\Domain\MunicipalCouncil\Entity\Deliberation;
use App\Domain\MunicipalCouncil\Repository\CouncilSessionRepositoryInterface;
use App\Domain\MunicipalCouncil\ValueObject\Attendance;
use App\Domain\MunicipalCouncil\ValueObject\AttendanceStatus;
use App\Domain\MunicipalCouncil\ValueObject\CouncilorId;
use App\Domain\MunicipalCouncil\ValueObject\CouncilSessionId;
use App\Domain\MunicipalCouncil\ValueObject\DeliberationId;
use App\Domain\MunicipalCouncil\ValueObject\DeliberationStatus;
use App\Domain\MunicipalCouncil\ValueObject\SessionStatus;
use App\Domain\MunicipalCouncil\ValueObject\SessionType;
use App\Domain\MunicipalCouncil\ValueObject\VoteResult;
use App\Infrastructure\Persistence\Doctrine\Entity\AttendanceRecord;
use App\Infrastructure\Persistence\Doctrine\Entity\CouncilSessionRecord;
use App\Infrastructure\Persistence\Doctrine\Entity\DeliberationRecord;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineCouncilSessionRepository implements CouncilSessionRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em) {}

    public function save(CouncilSession $session): void
    {
        $record = $this->em->find(CouncilSessionRecord::class, $session->getId()->getValue())
            ?? new CouncilSessionRecord();

        $record->id                  = $session->getId()->getValue();
        $record->townHallCode        = $session->getTownHallCode();
        $record->sessionDate         = $session->getDate();
        $record->orderOfBusiness     = $session->getOrderOfBusiness();
        $record->sessionType         = $session->getType()->value;
        $record->status              = $session->getStatus()->value;
        $record->invitationsSent     = $session->areInvitationsSent();
        $record->deliberationSequence = $session->getDeliberationSequence();

        $record->attendances->clear();
        foreach ($session->getAttendances() as $attendance) {
            $ar               = new AttendanceRecord();
            $ar->session      = $record;
            $ar->councilorId  = $attendance->getCouncilorId()->getValue();
            $ar->status       = $attendance->getStatus()->value;
            $ar->proxyHolderId = $attendance->getProxyHolderId()?->getValue();
            $record->attendances->add($ar);
        }

        $record->deliberations->clear();
        foreach ($session->getDeliberations() as $deliberation) {
            $dr               = new DeliberationRecord();
            $dr->session      = $record;
            $dr->id           = $deliberation->getId()->getValue();
            $dr->number       = $deliberation->getNumber();
            $dr->title        = $deliberation->getTitle();
            $dr->description  = $deliberation->getDescription();
            $dr->status       = $deliberation->getStatus()->value;
            $dr->votePour      = $deliberation->getVoteResult()?->getPour();
            $dr->voteContre    = $deliberation->getVoteResult()?->getContre();
            $dr->voteAbstention = $deliberation->getVoteResult()?->getAbstention();
            $record->deliberations->add($dr);
        }

        $this->em->persist($record);
        $this->em->flush();
    }

    public function findById(CouncilSessionId $id): ?CouncilSession
    {
        $record = $this->em->find(CouncilSessionRecord::class, $id->getValue());

        return $record ? $this->toDomain($record) : null;
    }

    public function findByTownHallCode(string $code): array
    {
        $records = $this->em->getRepository(CouncilSessionRecord::class)
            ->findBy(['townHallCode' => $code]);

        return array_map($this->toDomain(...), $records);
    }

    private function toDomain(CouncilSessionRecord $record): CouncilSession
    {
        $attendances = $record->attendances->map(
            fn(AttendanceRecord $ar) => new Attendance(
                councilorId: CouncilorId::fromString($ar->councilorId),
                status: AttendanceStatus::from($ar->status),
                proxyHolderId: $ar->proxyHolderId ? CouncilorId::fromString($ar->proxyHolderId) : null,
            )
        )->toArray();

        $deliberations = $record->deliberations->map(
            fn(DeliberationRecord $dr) => Deliberation::reconstitute(
                id: DeliberationId::fromString($dr->id),
                number: $dr->number,
                title: $dr->title,
                description: $dr->description,
                status: DeliberationStatus::from($dr->status),
                voteResult: $dr->votePour !== null
                    ? new VoteResult($dr->votePour, $dr->voteContre, $dr->voteAbstention)
                    : null,
            )
        )->toArray();

        return CouncilSession::reconstitute(
            id: CouncilSessionId::fromString($record->id),
            townHallCode: $record->townHallCode,
            date: $record->sessionDate,
            orderOfBusiness: $record->orderOfBusiness,
            type: SessionType::from($record->sessionType),
            status: SessionStatus::from($record->status),
            invitationsSent: $record->invitationsSent,
            deliberationSequence: $record->deliberationSequence,
            attendances: $attendances,
            deliberations: $deliberations,
        );
    }
}
