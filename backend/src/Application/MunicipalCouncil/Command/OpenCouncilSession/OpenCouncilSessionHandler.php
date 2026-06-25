<?php

declare(strict_types=1);

namespace App\Application\MunicipalCouncil\Command\OpenCouncilSession;

use App\Domain\MunicipalCouncil\Exception\CouncilSessionNotFoundException;
use App\Domain\MunicipalCouncil\Repository\CouncilSessionRepositoryInterface;
use App\Domain\MunicipalCouncil\Repository\CouncilorRepositoryInterface;
use App\Domain\MunicipalCouncil\ValueObject\CouncilSessionId;

final class OpenCouncilSessionHandler
{
    public function __construct(
        private readonly CouncilSessionRepositoryInterface $sessionRepository,
        private readonly CouncilorRepositoryInterface $councilorRepository,
    ) {}

    public function handle(OpenCouncilSessionCommand $command): void
    {
        $session = $this->sessionRepository->findById(
            CouncilSessionId::fromString($command->sessionId)
        );

        if ($session === null) {
            throw new CouncilSessionNotFoundException(
                sprintf('Séance "%s" introuvable.', $command->sessionId)
            );
        }

        $totalActiveCouncilors = $this->councilorRepository->countActive();

        $session->open($totalActiveCouncilors);

        $this->sessionRepository->save($session);
    }
}
