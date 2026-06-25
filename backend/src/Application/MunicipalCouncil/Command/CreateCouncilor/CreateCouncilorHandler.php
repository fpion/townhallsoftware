<?php

declare(strict_types=1);

namespace App\Application\MunicipalCouncil\Command\CreateCouncilor;

use App\Domain\MunicipalCouncil\Entity\Councilor;
use App\Domain\MunicipalCouncil\Repository\CouncilorRepositoryInterface;
use App\Domain\MunicipalCouncil\ValueObject\CouncilorId;
use App\Domain\MunicipalCouncil\ValueObject\CouncilorRole;
use App\Domain\TownHall\Exception\TownHallNotFoundException;
use App\Domain\TownHall\Repository\TownHallRepositoryInterface;

final class CreateCouncilorHandler
{
    public function __construct(
        private readonly CouncilorRepositoryInterface $councilorRepository,
        private readonly TownHallRepositoryInterface $townHallRepository,
    ) {}

    public function handle(CreateCouncilorCommand $command): CouncilorId
    {
        if ($this->townHallRepository->findByCode($command->townHallCode) === null) {
            throw new TownHallNotFoundException(
                sprintf('Mairie "%s" introuvable.', $command->townHallCode)
            );
        }

        $id = CouncilorId::generate();

        $councilor = new Councilor(
            id: $id,
            firstName: $command->firstName,
            lastName: $command->lastName,
            role: CouncilorRole::CONSEILLER,
            email: $command->email,
            townHallCode: $command->townHallCode,
        );

        $this->councilorRepository->save($councilor);

        return $id;
    }
}
