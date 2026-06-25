<?php

declare(strict_types=1);

namespace App\Domain\MunicipalCouncil\Entity;

use App\Domain\MunicipalCouncil\Exception\DeliberationAlreadyVotedException;
use App\Domain\MunicipalCouncil\ValueObject\DeliberationId;
use App\Domain\MunicipalCouncil\ValueObject\DeliberationStatus;
use App\Domain\MunicipalCouncil\ValueObject\VoteResult;

class Deliberation
{
    private DeliberationStatus $status;
    private ?VoteResult $voteResult = null;

    public function __construct(
        private readonly DeliberationId $id,
        private readonly string $number,
        private readonly string $title,
        private readonly string $description,
    ) {
        if (trim($title) === '') {
            throw new \InvalidArgumentException('Le titre de la délibération ne peut pas être vide.');
        }
        if (trim($number) === '') {
            throw new \InvalidArgumentException('Le numéro de la délibération ne peut pas être vide.');
        }

        $this->status = DeliberationStatus::PENDING;
    }

    public function vote(VoteResult $voteResult): void
    {
        if ($this->status !== DeliberationStatus::PENDING) {
            throw new DeliberationAlreadyVotedException(
                sprintf('La délibération "%s" a déjà été soumise au vote.', $this->number)
            );
        }

        $this->voteResult = $voteResult;
        $this->status = $voteResult->isAdopted()
            ? DeliberationStatus::ADOPTED
            : DeliberationStatus::REJECTED;
    }

    public function withdraw(): void
    {
        if ($this->status !== DeliberationStatus::PENDING) {
            throw new \LogicException(
                sprintf('Impossible de retirer la délibération "%s" : elle a déjà été soumise au vote.', $this->number)
            );
        }

        $this->status = DeliberationStatus::WITHDRAWN;
    }

    public function getId(): DeliberationId
    {
        return $this->id;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStatus(): DeliberationStatus
    {
        return $this->status;
    }

    public function getVoteResult(): ?VoteResult
    {
        return $this->voteResult;
    }

    public function isPending(): bool
    {
        return $this->status === DeliberationStatus::PENDING;
    }

    public function isAdopted(): bool
    {
        return $this->status === DeliberationStatus::ADOPTED;
    }

    public static function reconstitute(
        DeliberationId $id,
        string $number,
        string $title,
        string $description,
        DeliberationStatus $status,
        ?VoteResult $voteResult,
    ): self {
        $deliberation = new self($id, $number, $title, $description);
        $deliberation->status = $status;
        $deliberation->voteResult = $voteResult;

        return $deliberation;
    }
}
