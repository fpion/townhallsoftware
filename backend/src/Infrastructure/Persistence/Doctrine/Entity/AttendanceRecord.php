<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'session_attendances')]
class AttendanceRecord
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public int $id;

    #[ORM\ManyToOne(targetEntity: CouncilSessionRecord::class, inversedBy: 'attendances')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    public CouncilSessionRecord $session;

    #[ORM\Column(type: 'string', length: 36)]
    public string $councilorId;

    #[ORM\Column(type: 'string', length: 30)]
    public string $status;

    #[ORM\Column(type: 'string', length: 36, nullable: true)]
    public ?string $proxyHolderId;
}
