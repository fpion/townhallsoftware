<?php

declare(strict_types=1);

namespace App\Domain\MunicipalCouncil\ValueObject;

final class Attendance
{
    public function __construct(
        private readonly CouncilorId $councilorId,
        private readonly AttendanceStatus $status,
        /** @var CouncilorId|null Conseiller porteur du pouvoir en cas de procuration */
        private readonly ?CouncilorId $proxyHolderId = null,
    ) {
        if ($status === AttendanceStatus::PROCURATION && $proxyHolderId === null) {
            throw new \InvalidArgumentException(
                'Un porteur de procuration doit être désigné lorsque le statut est PROCURATION.'
            );
        }

        if ($status !== AttendanceStatus::PROCURATION && $proxyHolderId !== null) {
            throw new \InvalidArgumentException(
                'Un porteur de procuration ne peut être désigné que pour le statut PROCURATION.'
            );
        }
    }

    public function getCouncilorId(): CouncilorId
    {
        return $this->councilorId;
    }

    public function getStatus(): AttendanceStatus
    {
        return $this->status;
    }

    public function getProxyHolderId(): ?CouncilorId
    {
        return $this->proxyHolderId;
    }

    public function countsForQuorum(): bool
    {
        return $this->status->countsForQuorum();
    }
}
