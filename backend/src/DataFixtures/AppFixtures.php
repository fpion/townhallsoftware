<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Domain\MunicipalCouncil\ValueObject\AttendanceStatus;
use App\Domain\MunicipalCouncil\ValueObject\CouncilorRole;
use App\Domain\MunicipalCouncil\ValueObject\DeliberationStatus;
use App\Domain\MunicipalCouncil\ValueObject\SessionStatus;
use App\Domain\MunicipalCouncil\ValueObject\SessionType;
use App\Infrastructure\Persistence\Doctrine\Entity\AttendanceRecord;
use App\Infrastructure\Persistence\Doctrine\Entity\CouncilorRecord;
use App\Infrastructure\Persistence\Doctrine\Entity\CouncilSessionRecord;
use App\Infrastructure\Persistence\Doctrine\Entity\DeliberationRecord;
use App\Infrastructure\Persistence\Doctrine\Entity\TownHallRecord;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Seed data for local development: one town hall, a full municipal council,
 * a closed session with recorded votes, and an upcoming planned session.
 *
 * IDs are hard-coded (deterministic) so reloading fixtures yields stable data.
 */
class AppFixtures extends Fixture
{
    private const TOWN_HALL_CODE = 'VINC';

    /** @var array<string, string> councilor key => UUID */
    private const COUNCILOR_IDS = [
        'maire'       => '11111111-1111-4111-8111-111111111111',
        'adjoint1'    => '22222222-2222-4222-8222-222222222222',
        'adjoint2'    => '33333333-3333-4333-8333-333333333333',
        'delegue'     => '44444444-4444-4444-8444-444444444444',
        'conseiller1' => '55555555-5555-4555-8555-555555555555',
        'conseiller2' => '66666666-6666-4666-8666-666666666666',
        'conseiller3' => '77777777-7777-4777-8777-777777777777',
    ];

    public function load(ObjectManager $manager): void
    {
        $this->loadTownHall($manager);
        $councilors = $this->loadCouncilors($manager);
        $this->loadClosedSession($manager, $councilors);
        $this->loadPlannedSession($manager);

        $manager->flush();
    }

    private function loadTownHall(ObjectManager $manager): void
    {
        $townHall = new TownHallRecord();
        $townHall->code = self::TOWN_HALL_CODE;
        $townHall->name = 'Mairie de Vincennes';
        $townHall->street = '53 bis rue de Fontenay';
        $townHall->city = 'Vincennes';
        $townHall->postalCode = '94300';
        $townHall->population = 49891;

        $manager->persist($townHall);
    }

    /**
     * @return array<string, CouncilorRecord>
     */
    private function loadCouncilors(ObjectManager $manager): array
    {
        $definitions = [
            'maire'       => ['Charlotte', 'Libert', CouncilorRole::MAIRE, true],
            'adjoint1'    => ['Antoine', 'Mercier', CouncilorRole::MAIRE_ADJOINT, true],
            'adjoint2'    => ['Sophie', 'Nguyen', CouncilorRole::MAIRE_ADJOINT, true],
            'delegue'     => ['Karim', 'Benali', CouncilorRole::CONSEILLER_DELEGUE, true],
            'conseiller1' => ['Elodie', 'Rousseau', CouncilorRole::CONSEILLER, true],
            'conseiller2' => ['Marc', 'Dubois', CouncilorRole::CONSEILLER, true],
            'conseiller3' => ['Fatou', 'Diallo', CouncilorRole::CONSEILLER, false],
        ];

        $records = [];
        foreach ($definitions as $key => [$firstName, $lastName, $role, $active]) {
            $councilor = new CouncilorRecord();
            $councilor->id = self::COUNCILOR_IDS[$key];
            $councilor->firstName = $firstName;
            $councilor->lastName = $lastName;
            $councilor->email = sprintf(
                '%s.%s@vincennes.fr',
                strtolower($firstName),
                strtolower($lastName),
            );
            $councilor->role = $role->value;
            $councilor->active = $active;

            $manager->persist($councilor);
            $records[$key] = $councilor;
        }

        return $records;
    }

    /**
     * @param array<string, CouncilorRecord> $councilors
     */
    private function loadClosedSession(ObjectManager $manager, array $councilors): void
    {
        $session = new CouncilSessionRecord();
        $session->id = 'a0000000-0000-4000-8000-000000000001';
        $session->townHallCode = self::TOWN_HALL_CODE;
        $session->sessionDate = new \DateTimeImmutable('2026-03-12 19:00:00');
        $session->orderOfBusiness = "1. Approbation du budget primitif 2026\n"
            . "2. Subventions aux associations sportives\n"
            . "3. Renovation energetique de l'ecole Jean-Moulin";
        $session->sessionType = SessionType::ORDINARY->value;
        $session->status = SessionStatus::CLOSED->value;
        $session->invitationsSent = true;
        $session->deliberationSequence = 3;

        // Presences
        $presence = [
            'maire'       => [AttendanceStatus::PRESENT, null],
            'adjoint1'    => [AttendanceStatus::PRESENT, null],
            'adjoint2'    => [AttendanceStatus::PRESENT, null],
            'delegue'     => [AttendanceStatus::PROCURATION, self::COUNCILOR_IDS['maire']],
            'conseiller1' => [AttendanceStatus::PRESENT, null],
            'conseiller2' => [AttendanceStatus::ABSENT_EXCUSE, null],
            'conseiller3' => [AttendanceStatus::ABSENT, null],
        ];
        foreach ($presence as $key => [$status, $proxyHolderId]) {
            $attendance = new AttendanceRecord();
            $attendance->session = $session;
            $attendance->councilorId = $councilors[$key]->id;
            $attendance->status = $status->value;
            $attendance->proxyHolderId = $proxyHolderId;

            $session->attendances->add($attendance);
        }

        // Deliberations
        $deliberations = [
            [
                'id' => 'b0000000-0000-4000-8000-000000000001',
                'number' => '2026-001',
                'title' => 'Approbation du budget primitif 2026',
                'description' => 'Vote du budget primitif de la commune pour l\'exercice 2026, '
                    . 'equilibre en recettes et en depenses a 87,4 millions d\'euros.',
                'status' => DeliberationStatus::ADOPTED,
                'votes' => [5, 0, 0],
            ],
            [
                'id' => 'b0000000-0000-4000-8000-000000000002',
                'number' => '2026-002',
                'title' => 'Subventions aux associations sportives',
                'description' => 'Attribution des subventions annuelles aux associations sportives '
                    . 'de la commune pour un montant total de 240 000 euros.',
                'status' => DeliberationStatus::ADOPTED,
                'votes' => [4, 0, 1],
            ],
            [
                'id' => 'b0000000-0000-4000-8000-000000000003',
                'number' => '2026-003',
                'title' => 'Renovation energetique de l\'ecole Jean-Moulin',
                'description' => 'Lancement du marche de travaux pour la renovation energetique '
                    . 'du groupe scolaire Jean-Moulin estime a 1,2 million d\'euros.',
                'status' => DeliberationStatus::REJECTED,
                'votes' => [2, 3, 0],
            ],
        ];
        foreach ($deliberations as $data) {
            $deliberation = new DeliberationRecord();
            $deliberation->id = $data['id'];
            $deliberation->session = $session;
            $deliberation->number = $data['number'];
            $deliberation->title = $data['title'];
            $deliberation->description = $data['description'];
            $deliberation->status = $data['status']->value;
            [$deliberation->votePour, $deliberation->voteContre, $deliberation->voteAbstention] = $data['votes'];

            $session->deliberations->add($deliberation);
        }

        $manager->persist($session);
    }

    private function loadPlannedSession(ObjectManager $manager): void
    {
        $session = new CouncilSessionRecord();
        $session->id = 'a0000000-0000-4000-8000-000000000002';
        $session->townHallCode = self::TOWN_HALL_CODE;
        $session->sessionDate = new \DateTimeImmutable('2026-06-25 19:00:00');
        $session->orderOfBusiness = "1. Compte administratif 2025\n"
            . "2. Plan local d'urbanisme : revision allegee\n"
            . "3. Questions diverses";
        $session->sessionType = SessionType::ORDINARY->value;
        $session->status = SessionStatus::PLANNED->value;
        $session->invitationsSent = false;
        $session->deliberationSequence = 0;

        $manager->persist($session);
    }
}
