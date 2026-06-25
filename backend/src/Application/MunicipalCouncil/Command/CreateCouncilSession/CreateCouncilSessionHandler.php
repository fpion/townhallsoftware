<?php

declare(strict_types=1);

namespace App\Application\MunicipalCouncil\Command\CreateCouncilSession;

use App\Domain\MunicipalCouncil\Entity\CouncilSession;
use App\Domain\MunicipalCouncil\Repository\CouncilSessionRepositoryInterface;
use App\Domain\MunicipalCouncil\ValueObject\CouncilSessionId;
use App\Domain\TownHall\Exception\TownHallNotFoundException;
use App\Domain\TownHall\Repository\TownHallRepositoryInterface;

final class CreateCouncilSessionHandler
{
    public function __construct(
        private readonly CouncilSessionRepositoryInterface $sessionRepository,
        private readonly TownHallRepositoryInterface $townHallRepository,
    ) {}

    public function handle(CreateCouncilSessionCommand $command): CouncilSessionId
    {
        if ($this->townHallRepository->findByCode($command->townHallCode) === null) {
            throw new TownHallNotFoundException(
                sprintf('Mairie "%s" introuvable.', $command->townHallCode)
            );
        }

        $id = CouncilSessionId::generate();

        $session = new CouncilSession(
            $id,
            $command->townHallCode,
            $command->date,
            $command->sessionType,
        );

        $this->sessionRepository->save($session);

        return $id;
    }
}
