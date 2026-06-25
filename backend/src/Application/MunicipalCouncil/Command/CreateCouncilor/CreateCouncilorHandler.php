<?php

declare(strict_types=1);

namespace App\Application\MunicipalCouncil\Command\CreateCouncilor;

use App\Domain\MunicipalCouncil\Entity\Councilor;
use App\Domain\MunicipalCouncil\Repository\CouncilorRepositoryInterface;
use App\Domain\MunicipalCouncil\ValueObject\CouncilorId;
use App\Domain\MunicipalCouncil\ValueObject\CouncilorRole;

final class CreateCouncilorHandler
{
    public function __construct(
        private readonly CouncilorRepositoryInterface $councilorRepository,
    ) {}

    public function handle(CreateCouncilorCommand $command): CouncilorId
    {
        $id = CouncilorId::generate();

        $councilor = new Councilor(
            id: $id,
            firstName: $command->firstName,
            lastName: $command->lastName,
            role: CouncilorRole::CONSEILLER,
            email: $command->email,
        );

        $this->councilorRepository->save($councilor);

        return $id;
    }
}
