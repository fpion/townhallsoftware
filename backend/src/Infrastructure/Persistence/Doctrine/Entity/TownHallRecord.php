<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'town_halls')]
class TownHallRecord
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 20)]
    public string $code;

    #[ORM\Column(type: 'string', length: 255)]
    public string $name;

    #[ORM\Column(type: 'string', length: 255)]
    public string $street;

    #[ORM\Column(type: 'string', length: 255)]
    public string $city;

    #[ORM\Column(type: 'string', length: 10)]
    public string $postalCode;

    #[ORM\Column(type: 'integer')]
    public int $population;
}
