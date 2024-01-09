<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CarRepository::class)]

#[ApiResource(
    normalizationContext: ['groups' => ['car:read']],
    denormalizationContext: ['groups' => ['car:write']],
    paginationItemsPerPage: 20,
)]


class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['car:read', 'review:read'])]
    private int $id;


    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['car:read', 'car:write', 'review:read'])]
    private string $brand;


    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['car:read', 'car:write', 'review:read'])]
    private string $model;

    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['car:read', 'car:write', 'review:read'])]
    private string $color;
    

    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'car', cascade: ['remove'])]
    #[Groups(['car:read'])]
    private $reviews;


    public function __construct()
    {
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }


    public function getReviews()
    {
        return $this->reviews;
    }

}
