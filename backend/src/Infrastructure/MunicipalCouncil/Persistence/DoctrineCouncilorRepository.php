<?php

declare(strict_types=1);

namespace App\Infrastructure\MunicipalCouncil\Persistence;

use App\Domain\MunicipalCouncil\Entity\Councilor;
use App\Domain\MunicipalCouncil\Repository\CouncilorRepositoryInterface;
use App\Domain\MunicipalCouncil\ValueObject\CouncilorId;
use App\Domain\MunicipalCouncil\ValueObject\CouncilorRole;
use App\Infrastructure\Persistence\Doctrine\Entity\CouncilorRecord;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineCouncilorRepository implements CouncilorRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em) {}

    public function save(Councilor $councilor): void
    {
        $record = $this->em->find(CouncilorRecord::class, $councilor->getId()->getValue())
            ?? new CouncilorRecord();

        $record->id        = $councilor->getId()->getValue();
        $record->firstName = $councilor->getFirstName();
        $record->lastName  = $councilor->getLastName();
        $record->email     = $councilor->getEmail();
        $record->role      = $councilor->getRole()->value;
        $record->active    = $councilor->isActive();

        $this->em->persist($record);
        $this->em->flush();
    }

    public function findById(CouncilorId $id): ?Councilor
    {
        $record = $this->em->find(CouncilorRecord::class, $id->getValue());

        return $record ? $this->toDomain($record) : null;
    }

    public function findAll(): array
    {
        return array_map(
            $this->toDomain(...),
            $this->em->getRepository(CouncilorRecord::class)->findAll(),
        );
    }

    public function findAllActive(): array
    {
        return array_map(
            $this->toDomain(...),
            $this->em->getRepository(CouncilorRecord::class)->findBy(['active' => true]),
        );
    }

    public function countActive(): int
    {
        return $this->em->getRepository(CouncilorRecord::class)->count(['active' => true]);
    }

    private function toDomain(CouncilorRecord $record): Councilor
    {
        return new Councilor(
            id: CouncilorId::fromString($record->id),
            firstName: $record->firstName,
            lastName: $record->lastName,
            role: CouncilorRole::from($record->role),
            email: $record->email,
            active: $record->active,
        );
    }
}
