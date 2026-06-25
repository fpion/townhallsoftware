<?php

declare(strict_types=1);

namespace App\Application\TownHall\Command\CreateTownHall;

use App\Domain\TownHall\Address;
use App\Domain\TownHall\Repository\TownHallRepositoryInterface;
use App\Domain\TownHall\TownHall;
use App\Domain\TownHall\TownHallCode;

final class CreateTownHallHandler
{
    public function __construct(
        private readonly TownHallRepositoryInterface $townHallRepository,
    ) {}

    public function handle(CreateTownHallCommand $command): void
    {
        $townHall = new TownHall(
            code: new TownHallCode($command->code),
            name: $command->name,
            address: new Address($command->street, $command->city, $command->postalCode),
            population: $command->population,
        );

        $this->townHallRepository->save($townHall);
    }
}
