<?php

namespace App\Service;

use App\Entity\Car;
use App\Repository\ReviewRepository;
use PhpParser\Lexer\TokenEmulator\ReverseEmulator;

class FetchLatestReviewsWithRateConditionService
{
    public function __construct(
        private ReviewRepository $reviewRepository
    )
    {
    }

    public function __invoke(Car $car, ?int $limit = null, ?int $minRating = null)
    {
        $limit = $limit ?? 10;
        $minRating = $minRating ?? 1;

        $result = $this->reviewRepository->getLatestReviewsByRateCondition($car->getId(), $limit, $minRating);

        return $result;
    }
}
