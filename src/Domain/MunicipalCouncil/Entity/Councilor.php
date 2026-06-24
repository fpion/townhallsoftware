<?php

declare(strict_types=1);

namespace App\Domain\MunicipalCouncil\Entity;

use App\Domain\MunicipalCouncil\ValueObject\CouncilorId;
use App\Domain\MunicipalCouncil\ValueObject\CouncilorRole;

class Councilor
{
    public function __construct(
        private readonly CouncilorId $id,
        private string $firstName,
        private string $lastName,
        private CouncilorRole $role,
        private bool $active = true,
    ) {
        if (trim($firstName) === '') {
            throw new \InvalidArgumentException('Le prénom du conseiller ne peut pas être vide.');
        }
        if (trim($lastName) === '') {
            throw new \InvalidArgumentException('Le nom du conseiller ne peut pas être vide.');
        }
    }

    public function getId(): CouncilorId
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getRole(): CouncilorRole
    {
        return $this->role;
    }

    public function isMaire(): bool
    {
        return $this->role === CouncilorRole::MAIRE;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function changeRole(CouncilorRole $role): void
    {
        $this->role = $role;
    }

    public function deactivate(): void
    {
        $this->active = false;
    }
}
