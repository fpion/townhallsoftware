<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'councilors')]
class CouncilorRecord
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36)]
    public string $id;

    #[ORM\Column(type: 'string', length: 255)]
    public string $firstName;

    #[ORM\Column(type: 'string', length: 255)]
    public string $lastName;

    #[ORM\Column(type: 'string', length: 255)]
    public string $email;

    #[ORM\Column(type: 'string', length: 50)]
    public string $role;

    #[ORM\Column(type: 'boolean')]
    public bool $active;
}
