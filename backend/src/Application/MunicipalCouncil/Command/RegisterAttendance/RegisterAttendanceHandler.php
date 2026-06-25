<?php

declare(strict_types=1);

namespace App\Application\MunicipalCouncil\Command\RegisterAttendance;

use App\Domain\MunicipalCouncil\Exception\CouncilSessionNotFoundException;
use App\Domain\MunicipalCouncil\Exception\CouncilorNotFoundException;
use App\Domain\MunicipalCouncil\Repository\CouncilSessionRepositoryInterface;
use App\Domain\MunicipalCouncil\Repository\CouncilorRepositoryInterface;
use App\Domain\MunicipalCouncil\ValueObject\CouncilorId;
use App\Domain\MunicipalCouncil\ValueObject\CouncilSessionId;

final class RegisterAttendanceHandler
{
    public function __construct(
        private readonly CouncilSessionRepositoryInterface $sessionRepository,
        private readonly CouncilorRepositoryInterface $councilorRepository,
    ) {}

    public function handle(RegisterAttendanceCommand $command): void
    {
        $session = $this->sessionRepository->findById(
            CouncilSessionId::fromString($command->sessionId)
        );

        if ($session === null) {
            throw new CouncilSessionNotFoundException(
                sprintf('Séance "%s" introuvable.', $command->sessionId)
            );
        }

        $councilor = $this->councilorRepository->findById(
            CouncilorId::fromString($command->councilorId)
        );

        if ($councilor === null) {
            throw new CouncilorNotFoundException(
                sprintf('Conseiller "%s" introuvable.', $command->councilorId)
            );
        }

        $proxyHolderId = $command->proxyHolderId !== null
            ? CouncilorId::fromString($command->proxyHolderId)
            : null;

        $session->registerAttendance($councilor, $command->status, $proxyHolderId);

        $this->sessionRepository->save($session);
    }
}
