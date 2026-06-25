<?php

declare(strict_types=1);

namespace App\Application\MunicipalCouncil\Command\AssignCouncilorRole;

use App\Domain\MunicipalCouncil\Exception\CouncilorNotFoundException;
use App\Domain\MunicipalCouncil\Exception\MaireAlreadyExistsException;
use App\Domain\MunicipalCouncil\Exception\MaxAdjointsReachedException;
use App\Domain\MunicipalCouncil\Repository\CouncilorRepositoryInterface;
use App\Domain\MunicipalCouncil\ValueObject\CouncilorId;
use App\Domain\MunicipalCouncil\ValueObject\CouncilorRole;
use App\Domain\MunicipalCouncil\ValueObject\CouncilCompositionRule;
use App\Domain\TownHall\Exception\TownHallNotFoundException;
use App\Domain\TownHall\Repository\TownHallRepositoryInterface;

/**
 * Attribue un rôle à un conseiller municipal en appliquant les règles de composition légales.
 *
 * Règles vérifiées :
 * - Un seul Maire en exercice à la fois
 * - Nombre d'adjoints ≤ floor(nbConseillers × 0,30) — art. L2122-2 CGCT
 */
final class AssignCouncilorRoleHandler
{
    public function __construct(
        private readonly CouncilorRepositoryInterface $councilorRepository,
        private readonly TownHallRepositoryInterface $townHallRepository,
    ) {}

    public function handle(AssignCouncilorRoleCommand $command): void
    {
        $councilor = $this->councilorRepository->findById(
            CouncilorId::fromString($command->councilorId)
        );

        if ($councilor === null) {
            throw new CouncilorNotFoundException(
                sprintf('Conseiller "%s" introuvable.', $command->councilorId)
            );
        }

        $townHall = $this->townHallRepository->findByCode($command->townHallCode);

        if ($townHall === null) {
            throw new TownHallNotFoundException(
                sprintf('Mairie avec le code "%s" introuvable.', $command->townHallCode)
            );
        }

        $composition = CouncilCompositionRule::fromPopulation($townHall->getPopulation());
        $activeCouncilors = $this->councilorRepository->findAllActive();

        match ($command->newRole) {
            CouncilorRole::MAIRE => $this->guardMaireUnique($command->councilorId, $activeCouncilors),
            CouncilorRole::MAIRE_ADJOINT => $this->guardMaxAdjoints($command->councilorId, $activeCouncilors, $composition),
            default => null,
        };

        $councilor->changeRole($command->newRole);
        $this->councilorRepository->save($councilor);
    }

    /** @param \App\Domain\MunicipalCouncil\Entity\Councilor[] $activeCouncilors */
    private function guardMaireUnique(string $councilorId, array $activeCouncilors): void
    {
        foreach ($activeCouncilors as $c) {
            if ($c->isMaire() && $c->getId()->getValue() !== $councilorId) {
                throw new MaireAlreadyExistsException(
                    sprintf(
                        'Un Maire est déjà en exercice (%s). Révoquez son mandat avant d\'en nommer un nouveau.',
                        $c->getFullName()
                    )
                );
            }
        }
    }

    /**
     * @param \App\Domain\MunicipalCouncil\Entity\Councilor[] $activeCouncilors
     */
    private function guardMaxAdjoints(string $councilorId, array $activeCouncilors, CouncilCompositionRule $composition): void
    {
        $currentCount = 0;
        foreach ($activeCouncilors as $c) {
            if ($c->getRole() === CouncilorRole::MAIRE_ADJOINT && $c->getId()->getValue() !== $councilorId) {
                $currentCount++;
            }
        }

        $max = $composition->maxAdjoints();

        if ($currentCount >= $max) {
            throw new MaxAdjointsReachedException(
                sprintf(
                    'Le nombre maximum d\'adjoints est atteint (%d/%d — art. L2122-2 CGCT).',
                    $currentCount,
                    $max,
                )
            );
        }
    }
}
