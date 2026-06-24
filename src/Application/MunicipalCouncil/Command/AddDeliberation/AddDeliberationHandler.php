<?php

declare(strict_types=1);

namespace App\Application\MunicipalCouncil\Command\AddDeliberation;

use App\Domain\MunicipalCouncil\Exception\CouncilSessionNotFoundException;
use App\Domain\MunicipalCouncil\Repository\CouncilSessionRepositoryInterface;
use App\Domain\MunicipalCouncil\ValueObject\CouncilSessionId;
use App\Domain\MunicipalCouncil\ValueObject\DeliberationId;

final class AddDeliberationHandler
{
    public function __construct(
        private readonly CouncilSessionRepositoryInterface $sessionRepository,
    ) {}

    public function handle(AddDeliberationCommand $command): DeliberationId
    {
        $session = $this->sessionRepository->findById(
            CouncilSessionId::fromString($command->sessionId)
        );

        if ($session === null) {
            throw new CouncilSessionNotFoundException(
                sprintf('Séance "%s" introuvable.', $command->sessionId)
            );
        }

        $deliberationId = $session->addDeliberation($command->title, $command->description);

        $this->sessionRepository->save($session);

        return $deliberationId;
    }
}
