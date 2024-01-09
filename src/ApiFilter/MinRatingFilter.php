<?php

namespace App\ApiFilter;

use ApiPlatform\Doctrine\Odm\Filter\AbstractFilter;
use ApiPlatform\Metadata\Operation;
use Doctrine\ODM\MongoDB\Aggregation\Builder;

final class MinRatingFilter extends AbstractFilter
{
    protected function filterProperty(
        string $property, 
        $values, 
        Builder $aggregationBuilder, 
        string $resourceClass, 
        Operation $operation = null, 
        array &$context = []
    ): void {
    }

    public function getDescription(string $resourceClass): array
    {
        return [
            'min_rating' => [
                'property' => 'min_rating',
                'type' => 'int',
                'required' => false,
                'swagger' => [
                    'description' => 'Minimum rating',
                    'name' => 'min_rating',
                ],
            ],
        ];
    }
}
