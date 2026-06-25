<?php

declare(strict_types=1);

namespace App\Application\MunicipalCouncil\Command\VoteOnDeliberation;

use App\Domain\MunicipalCouncil\Exception\CouncilSessionNotFoundException;
use App\Domain\MunicipalCouncil\Repository\CouncilSessionRepositoryInterface;
use App\Domain\MunicipalCouncil\ValueObject\CouncilSessionId;
use App\Domain\MunicipalCouncil\ValueObject\DeliberationId;
use App\Domain\MunicipalCouncil\ValueObject\VoteResult;

final class VoteOnDeliberationHandler
{
    public function __construct(
        private readonly CouncilSessionRepositoryInterface $sessionRepository,
    ) {}

    public function handle(VoteOnDeliberationCommand $command): void
    {
        $session = $this->sessionRepository->findById(
            CouncilSessionId::fromString($command->sessionId)
        );

        if ($session === null) {
            throw new CouncilSessionNotFoundException(
                sprintf('Séance "%s" introuvable.', $command->sessionId)
            );
        }

        $session->voteOnDeliberation(
            DeliberationId::fromString($command->deliberationId),
            new VoteResult($command->pour, $command->contre, $command->abstention),
        );

        $this->sessionRepository->save($session);
    }
}
