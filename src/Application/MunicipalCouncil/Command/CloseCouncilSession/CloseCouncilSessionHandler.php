<?php

declare(strict_types=1);

namespace App\Application\MunicipalCouncil\Command\CloseCouncilSession;

use App\Domain\MunicipalCouncil\Exception\CouncilSessionNotFoundException;
use App\Domain\MunicipalCouncil\Repository\CouncilSessionRepositoryInterface;
use App\Domain\MunicipalCouncil\ValueObject\CouncilSessionId;

final class CloseCouncilSessionHandler
{
    public function __construct(
        private readonly CouncilSessionRepositoryInterface $sessionRepository,
    ) {}

    public function handle(CloseCouncilSessionCommand $command): void
    {
        $session = $this->sessionRepository->findById(
            CouncilSessionId::fromString($command->sessionId)
        );

        if ($session === null) {
            throw new CouncilSessionNotFoundException(
                sprintf('Séance "%s" introuvable.', $command->sessionId)
            );
        }

        $session->close();

        $this->sessionRepository->save($session);
    }
}
