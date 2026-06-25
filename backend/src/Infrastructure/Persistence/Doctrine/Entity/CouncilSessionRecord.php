<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'council_sessions')]
class CouncilSessionRecord
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36)]
    public string $id;

    #[ORM\Column(type: 'string', length: 20)]
    public string $townHallCode;

    #[ORM\Column(type: 'datetime_immutable')]
    public \DateTimeImmutable $sessionDate;

    #[ORM\Column(type: 'string', length: 20)]
    public string $sessionType;

    #[ORM\Column(type: 'string', length: 20)]
    public string $status;

    #[ORM\Column(type: 'boolean')]
    public bool $invitationsSent;

    #[ORM\Column(type: 'integer')]
    public int $deliberationSequence;

    /** @var Collection<int, AttendanceRecord> */
    #[ORM\OneToMany(
        targetEntity: AttendanceRecord::class,
        mappedBy: 'session',
        cascade: ['persist', 'remove'],
        orphanRemoval: true,
    )]
    public Collection $attendances;

    /** @var Collection<int, DeliberationRecord> */
    #[ORM\OneToMany(
        targetEntity: DeliberationRecord::class,
        mappedBy: 'session',
        cascade: ['persist', 'remove'],
        orphanRemoval: true,
    )]
    public Collection $deliberations;

    public function __construct()
    {
        $this->attendances = new ArrayCollection();
        $this->deliberations = new ArrayCollection();
    }
}
