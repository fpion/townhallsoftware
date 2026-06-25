<?php

declare(strict_types=1);

namespace App\Infrastructure\TownHall\Persistence;

use App\Domain\TownHall\Address;
use App\Domain\TownHall\Repository\TownHallRepositoryInterface;
use App\Domain\TownHall\TownHall;
use App\Domain\TownHall\TownHallCode;
use App\Infrastructure\Persistence\Doctrine\Entity\TownHallRecord;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineTownHallRepository implements TownHallRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em) {}

    public function save(TownHall $townHall): void
    {
        $record = $this->em->find(TownHallRecord::class, $townHall->getCode()->getCode())
            ?? new TownHallRecord();

        $record->code       = $townHall->getCode()->getCode();
        $record->name       = $townHall->getName();
        $record->street     = $townHall->getAddress()->getStreet();
        $record->city       = $townHall->getAddress()->getCity();
        $record->postalCode = $townHall->getAddress()->getPostalCode();
        $record->population = $townHall->getPopulation();

        $this->em->persist($record);
        $this->em->flush();
    }

    public function findByCode(string $code): ?TownHall
    {
        $record = $this->em->find(TownHallRecord::class, $code);

        return $record ? $this->toDomain($record) : null;
    }

    public function findAll(): array
    {
        return array_map(
            $this->toDomain(...),
            $this->em->getRepository(TownHallRecord::class)->findAll(),
        );
    }

    private function toDomain(TownHallRecord $record): TownHall
    {
        return new TownHall(
            code: new TownHallCode($record->code),
            name: $record->name,
            address: new Address($record->street, $record->city, $record->postalCode),
            population: $record->population,
        );
    }
}
