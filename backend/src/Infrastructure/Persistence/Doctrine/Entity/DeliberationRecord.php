<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'session_deliberations')]
class DeliberationRecord
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36)]
    public string $id;

    #[ORM\ManyToOne(targetEntity: CouncilSessionRecord::class, inversedBy: 'deliberations')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    public CouncilSessionRecord $session;

    #[ORM\Column(type: 'string', length: 20)]
    public string $number;

    #[ORM\Column(type: 'string', length: 500)]
    public string $title;

    #[ORM\Column(type: 'text')]
    public string $description;

    #[ORM\Column(type: 'string', length: 20)]
    public string $status;

    #[ORM\Column(type: 'integer', nullable: true)]
    public ?int $votePour;

    #[ORM\Column(type: 'integer', nullable: true)]
    public ?int $voteContre;

    #[ORM\Column(type: 'integer', nullable: true)]
    public ?int $voteAbstention;
}
