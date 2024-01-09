<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ReviewRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
#[ApiResource]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    #[Assert\Range(min: 1, max: 10, notInRangeMessage: 'Star rating must be between 1 and 10')]
    #[Assert\NotBlank]
    private int $starrating;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $reviewtext = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getStarrating(): ?int
    {
        return $this->starrating;
    }

    public function setStarrating(int $starrating): static
    {
        $this->starrating = $starrating;

        return $this;
    }

    public function getReviewtext(): ?string
    {
        return $this->reviewtext;
    }

    public function setReviewtext(?string $reviewtext): static
    {
        $this->reviewtext = $reviewtext;

        return $this;
    }
}
