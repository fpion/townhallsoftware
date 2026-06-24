<?php

declare(strict_types=1);

namespace App\Domain\MunicipalCouncil\Entity;

use App\Domain\MunicipalCouncil\Event\AttendanceRegistered;
use App\Domain\MunicipalCouncil\Event\CouncilSessionClosed;
use App\Domain\MunicipalCouncil\Event\CouncilSessionCreated;
use App\Domain\MunicipalCouncil\Event\CouncilSessionOpened;
use App\Domain\MunicipalCouncil\Event\DeliberationAdded;
use App\Domain\MunicipalCouncil\Event\DeliberationVoted;
use App\Domain\MunicipalCouncil\Event\DomainEvent;
use App\Domain\MunicipalCouncil\Exception\CouncilorAlreadyRegisteredAttendanceException;
use App\Domain\MunicipalCouncil\Exception\DeliberationNotFoundException;
use App\Domain\MunicipalCouncil\Exception\QuorumNotReachedException;
use App\Domain\MunicipalCouncil\Exception\SessionAlreadyClosedException;
use App\Domain\MunicipalCouncil\Exception\SessionNotOpenException;
use App\Domain\MunicipalCouncil\ValueObject\Attendance;
use App\Domain\MunicipalCouncil\ValueObject\AttendanceStatus;
use App\Domain\MunicipalCouncil\ValueObject\CouncilorId;
use App\Domain\MunicipalCouncil\ValueObject\CouncilSessionId;
use App\Domain\MunicipalCouncil\ValueObject\DeliberationId;
use App\Domain\MunicipalCouncil\ValueObject\SessionStatus;
use App\Domain\MunicipalCouncil\ValueObject\VoteResult;

/**
 * Agrégat racine représentant une séance du conseil municipal.
 *
 * Règles métier :
 * - Le quorum est atteint lorsque la majorité des conseillers en exercice est présente
 *   (art. L2121-17 CGCT). La séance ne peut être ouverte sans quorum.
 * - Les délibérations ne peuvent être ajoutées et votées que pendant une séance ouverte.
 * - Un conseiller absent peut donner procuration à un conseiller présent (art. L2121-20 CGCT).
 */
class CouncilSession
{
    /** @var array<string, Attendance> Indexé par CouncilorId */
    private array $attendances = [];

    /** @var array<string, Deliberation> Indexé par DeliberationId */
    private array $deliberations = [];

    /** @var DomainEvent[] */
    private array $domainEvents = [];

    private SessionStatus $status;
    private int $deliberationSequence = 0;

    public function __construct(
        private readonly CouncilSessionId $id,
        private readonly string $townHallCode,
        private readonly \DateTimeImmutable $date,
        private readonly string $orderOfBusiness,
    ) {
        if (trim($townHallCode) === '') {
            throw new \InvalidArgumentException('Le code de la mairie ne peut pas être vide.');
        }
        if (trim($orderOfBusiness) === '') {
            throw new \InvalidArgumentException('L\'ordre du jour ne peut pas être vide.');
        }

        $this->status = SessionStatus::PLANNED;

        $this->domainEvents[] = new CouncilSessionCreated(
            $this->id,
            $this->townHallCode,
            $this->date,
            $this->orderOfBusiness,
            new \DateTimeImmutable(),
        );
    }

    // -------------------------------------------------------------------------
    // Gestion des présences
    // -------------------------------------------------------------------------

    public function registerAttendance(
        Councilor $councilor,
        AttendanceStatus $status,
        ?CouncilorId $proxyHolderId = null,
    ): void {
        if ($this->status === SessionStatus::CLOSED) {
            throw new SessionAlreadyClosedException(
                'Impossible d\'enregistrer une présence : la séance est clôturée.'
            );
        }

        $councilorKey = $councilor->getId()->getValue();

        if (isset($this->attendances[$councilorKey])) {
            throw new CouncilorAlreadyRegisteredAttendanceException(
                sprintf(
                    'La présence de "%s" a déjà été enregistrée pour cette séance.',
                    $councilor->getFullName()
                )
            );
        }

        $attendance = new Attendance($councilor->getId(), $status, $proxyHolderId);
        $this->attendances[$councilorKey] = $attendance;

        $this->domainEvents[] = new AttendanceRegistered(
            $this->id,
            $councilor->getId(),
            $status,
            new \DateTimeImmutable(),
        );
    }

    // -------------------------------------------------------------------------
    // Cycle de vie de la séance
    // -------------------------------------------------------------------------

    /**
     * Ouvre la séance après vérification du quorum.
     *
     * Le quorum est la majorité absolue des membres en exercice :
     * plancher(totalActifs / 2) + 1
     */
    public function open(int $totalActiveCouncilors): void
    {
        if ($this->status !== SessionStatus::PLANNED) {
            throw new \LogicException(
                'La séance ne peut être ouverte que si elle est à l\'état planifiée.'
            );
        }

        $presentCount = $this->getPresentCount();
        $quorum = (int) floor($totalActiveCouncilors / 2) + 1;

        if ($presentCount < $quorum) {
            throw new QuorumNotReachedException(
                sprintf(
                    'Quorum non atteint : %d présents sur %d conseillers en exercice (quorum requis : %d).',
                    $presentCount,
                    $totalActiveCouncilors,
                    $quorum,
                )
            );
        }

        $this->status = SessionStatus::OPEN;

        $this->domainEvents[] = new CouncilSessionOpened(
            $this->id,
            $this->date,
            $presentCount,
            new \DateTimeImmutable(),
        );
    }

    /**
     * Clôture la séance. Toutes les délibérations en attente sont considérées
     * comme retirées de l'ordre du jour.
     */
    public function close(): void
    {
        if ($this->status !== SessionStatus::OPEN) {
            throw new SessionNotOpenException(
                'La séance ne peut être clôturée que si elle est ouverte.'
            );
        }

        foreach ($this->deliberations as $deliberation) {
            if ($deliberation->isPending()) {
                $deliberation->withdraw();
            }
        }

        $this->status = SessionStatus::CLOSED;

        $this->domainEvents[] = new CouncilSessionClosed(
            $this->id,
            new \DateTimeImmutable(),
        );
    }

    // -------------------------------------------------------------------------
    // Gestion des délibérations
    // -------------------------------------------------------------------------

    public function addDeliberation(string $title, string $description): DeliberationId
    {
        if ($this->status !== SessionStatus::OPEN) {
            throw new SessionNotOpenException(
                'Les délibérations ne peuvent être ajoutées qu\'à une séance ouverte.'
            );
        }

        $this->deliberationSequence++;
        $number = sprintf(
            'D-%s-%03d',
            $this->date->format('Y'),
            $this->deliberationSequence,
        );

        $id = DeliberationId::generate();
        $deliberation = new Deliberation($id, $number, $title, $description);
        $this->deliberations[$id->getValue()] = $deliberation;

        $this->domainEvents[] = new DeliberationAdded(
            $this->id,
            $id,
            $number,
            $title,
            new \DateTimeImmutable(),
        );

        return $id;
    }

    public function voteOnDeliberation(DeliberationId $deliberationId, VoteResult $voteResult): void
    {
        if ($this->status !== SessionStatus::OPEN) {
            throw new SessionNotOpenException(
                'Le vote n\'est possible que pendant une séance ouverte.'
            );
        }

        $deliberation = $this->deliberations[$deliberationId->getValue()] ?? null;

        if ($deliberation === null) {
            throw new DeliberationNotFoundException(
                sprintf('Délibération "%s" introuvable dans cette séance.', $deliberationId)
            );
        }

        $deliberation->vote($voteResult);

        $this->domainEvents[] = new DeliberationVoted(
            $this->id,
            $deliberationId,
            $voteResult,
            $deliberation->getStatus(),
            new \DateTimeImmutable(),
        );
    }

    // -------------------------------------------------------------------------
    // Accesseurs
    // -------------------------------------------------------------------------

    public function getId(): CouncilSessionId
    {
        return $this->id;
    }

    public function getTownHallCode(): string
    {
        return $this->townHallCode;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getOrderOfBusiness(): string
    {
        return $this->orderOfBusiness;
    }

    public function getStatus(): SessionStatus
    {
        return $this->status;
    }

    /** @return Attendance[] */
    public function getAttendances(): array
    {
        return array_values($this->attendances);
    }

    /** @return Deliberation[] */
    public function getDeliberations(): array
    {
        return array_values($this->deliberations);
    }

    public function getPresentCount(): int
    {
        return count(
            array_filter(
                $this->attendances,
                fn(Attendance $a) => $a->countsForQuorum()
            )
        );
    }

    public function isOpen(): bool
    {
        return $this->status === SessionStatus::OPEN;
    }

    public function isClosed(): bool
    {
        return $this->status === SessionStatus::CLOSED;
    }

    // -------------------------------------------------------------------------
    // Domain Events
    // -------------------------------------------------------------------------

    /** @return DomainEvent[] */
    public function pullDomainEvents(): array
    {
        $events = $this->domainEvents;
        $this->domainEvents = [];

        return $events;
    }
}
