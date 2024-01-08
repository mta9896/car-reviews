<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CarRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: CarRepository::class)]
#[ORM\Table(name: 'cars')]
#[ApiResource]
class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    private int $id;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $brand = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $model = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $color = null;

    public function getId(): ?int{
        
        return $this->id;
    }
}
