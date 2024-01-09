<?php

namespace App\Controller;

use App\Entity\Car;
use App\Service\FetchLatestReviewsWithRateConditionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;

#[AsController]
class FetchLatestReviewsWithRateCondition extends AbstractController
{
    public function __construct(
        private FetchLatestReviewsWithRateConditionService $service
    )
    {
    }

    public function __invoke(
        Car $car, 
        #[MapQueryParameter("limit")] ?int $limit = null,
        #[MapQueryParameter("min_rating")] ?int $minRating = null,
    )
    {   
        return ($this->service)($car, $limit, $minRating);
    }
}
