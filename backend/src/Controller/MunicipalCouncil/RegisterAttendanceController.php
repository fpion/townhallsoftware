<?php

declare(strict_types=1);

namespace App\Controller\MunicipalCouncil;

use App\Application\MunicipalCouncil\Command\RegisterAttendance\RegisterAttendanceCommand;
use App\Application\MunicipalCouncil\Command\RegisterAttendance\RegisterAttendanceHandler;
use App\Domain\MunicipalCouncil\ValueObject\AttendanceStatus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * POST /api/council-sessions/{id}/attendances
 *
 * Body JSON :
 *   - councilorId   : string  (UUID du conseiller)
 *   - status        : string  ("present" | "absent_excuse" | "absent" | "procuration")
 *   - proxyHolderId : string  (UUID du porteur de procuration, requis si status = "procuration")
 */
#[Route('/api/council-sessions/{id}/attendances', name: 'api_council_session_register_attendance', methods: ['POST'])]
final class RegisterAttendanceController
{
    use ApiExceptionHandler;

    public function __construct(
        private readonly RegisterAttendanceHandler $handler,
    ) {}

    public function __invoke(string $id, Request $request): JsonResponse
    {
        try {
            $body = $this->parseBody($request);

            $statusValue = (string) $this->require($body, 'status');
            $status = AttendanceStatus::tryFrom($statusValue)
                ?? throw new \InvalidArgumentException(
                    sprintf(
                        'Statut de présence "%s" invalide. Valeurs acceptées : %s.',
                        $statusValue,
                        implode(', ', array_column(AttendanceStatus::cases(), 'value')),
                    )
                );

            $command = new RegisterAttendanceCommand(
                sessionId: $id,
                councilorId: (string) $this->require($body, 'councilorId'),
                status: $status,
                proxyHolderId: isset($body['proxyHolderId']) ? (string) $body['proxyHolderId'] : null,
            );

            $this->handler->handle($command);

            return $this->json(['message' => 'Présence enregistrée.'], 201);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
}
