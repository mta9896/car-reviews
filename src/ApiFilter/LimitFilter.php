<?php

namespace App\ApiFilter;

use ApiPlatform\Doctrine\Odm\Filter\AbstractFilter;
use ApiPlatform\Metadata\Operation;
use Doctrine\ODM\MongoDB\Aggregation\Builder;

final class LimitFilter extends AbstractFilter
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
            'limit' => [
                'property' => 'limit',
                'type' => 'int',
                'required' => false,
                'swagger' => [
                    'description' => 'Max number of results',
                    'name' => 'Limit',
                ],
            ],
        ];
    }
}
