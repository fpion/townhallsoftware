<?php

declare(strict_types=1);

namespace App\Application\MunicipalCouncil\Command\CreateCouncilSession;

use App\Domain\MunicipalCouncil\Entity\CouncilSession;
use App\Domain\MunicipalCouncil\Repository\CouncilSessionRepositoryInterface;
use App\Domain\MunicipalCouncil\ValueObject\CouncilSessionId;

final class CreateCouncilSessionHandler
{
    public function __construct(
        private readonly CouncilSessionRepositoryInterface $sessionRepository,
    ) {}

    public function handle(CreateCouncilSessionCommand $command): CouncilSessionId
    {
        $id = CouncilSessionId::generate();

        $session = new CouncilSession(
            $id,
            $command->townHallCode,
            $command->date,
            $command->orderOfBusiness,
            $command->sessionType,
        );

        $this->sessionRepository->save($session);

        return $id;
    }
}
