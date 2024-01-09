<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ReviewRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\ApiFilter\LimitFilter;
use App\Controller\FetchLatestReviewsWithRateCondition;
use ApiPlatform\Metadata\ApiFilter;
use App\ApiFilter\MinRatingFilter;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['review:read']],
    denormalizationContext: ['groups' => ['review:write']],
    paginationItemsPerPage: 20,
    operations: [
        new Post()
    ],
)]

#[ApiResource(
    uriTemplate: '/cars/{car}/reviews',
    uriVariables: [
        'car' => new Link(
            fromClass: Car::class,
            fromProperty: 'reviews',
        )
        ],
        operations: [
            new GetCollection(),
        ],
        normalizationContext: [
            'groups' => 'review:read'
        ]
)]

#[ApiResource(
    uriTemplate: '/cars/{car}/reviews/{id}',
    uriVariables: [
        'car' => new Link(
            fromClass: Car::class,
            fromProperty: 'reviews',
        ),
        'id' => new Link(
            fromClass: Review::class
        )
        ],
        operations: [
            new Get()
        ],
        normalizationContext: [
            'groups' => 'review:read'
        ]
)]

#[ApiResource(
    uriTemplate: '/cars/{car}/reviews/{id}',
    uriVariables: [
        'car' => new Link(
            fromClass: Car::class,
            fromProperty: 'reviews',
        ),
        'id' => new Link(
            fromClass: Review::class
        )
        ],
        operations: [
            new Put(), new Patch(), new Delete()
        ],
        normalizationContext: [
            'groups' => 'review:write'
        ]
)]

#[ApiResource(
    uriTemplate: '/car/{car}/latest-reviews',
    
    uriVariables: [
        'car' => new Link(
            fromClass: Car::class,
            fromProperty: 'reviews',
        )
    ],
    operations: [
        new GetCollection(
            name: 'latest-reviews',
            controller: FetchLatestReviewsWithRateCondition::class
        )
    ],
)]

#[ApiFilter(
    LimitFilter::class
)]

#[ApiFilter(
    MinRatingFilter::class
)]

class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['review:read', 'car:read'])]
    private int $id;


    #[ORM\Column]
    #[Assert\Range(min: 1, max: 10, notInRangeMessage: 'Star rating must be between 1 and 10')]
    #[Assert\NotBlank]
    #[Groups(['review:read', 'review:write', 'car:read'])]
    private int $starrating;


    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['review:read', 'review:write', 'car:read'])]
    private ?string $reviewtext = null;


    #[ORM\ManyToOne(targetEntity: Car::class, inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['review:write'])]
    private $car;


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

    public function getCar()
    {
        return $this->car;
    }

    public function setCar($car)
    {
        $this->car = $car;

        return $this;
    }
}
