<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Factory\CarFactory;
use App\Factory\ReviewFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class CarTest extends ApiTestCase
{
    use ResetDatabase, Factories;

    public function testGetCarsCollection(): void
    {
        CarFactory::createMany(40);

        $client = static::createClient();
        $client->request('GET', '/api/cars');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $responseData = $client->getResponse()->toArray();

        $this->assertJsonContains([
            '@context' => '/api/contexts/Car',
            '@id' => '/api/cars',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 40, 
        ]);

        $member = $responseData['hydra:member'][0];
        $this->assertArrayHasKey('@id', $member);
        $this->assertArrayHasKey('@type', $member);
        $this->assertArrayHasKey('id', $member);
        $this->assertArrayHasKey('brand', $member);
        $this->assertArrayHasKey('model', $member);
        $this->assertArrayHasKey('color', $member);
        $this->assertArrayHasKey('reviews', $member);
    }

    public function testGetSingleCar(): void
    {
        $car = CarFactory::createOne();
        $review = ReviewFactory::createOne([
            'car' => $car,
        ]);

        $client = static::createClient();
        $client->request('GET', '/api/cars/' . $car->getId());

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');


        $this->assertJsonContains([
            '@context' => '/api/contexts/Car',
            '@id' => '/api/cars/' . $car->getId(),
            '@type' => 'Car',
            'brand' => $car->getBrand(),
            'model' => $car->getModel(),
            'color' => $car->getColor(),
            'reviews' => [
                [
                    '@id' => '/api/cars/' . $car->getId() . '/reviews/' . $review->getId(),
                    '@type' => 'Review',
                    'id' =>  $review->getId(),
                    'starrating' =>  $review->getStarrating(),
                    'reviewtext' =>  $review->getReviewtext(),
                ]
            ]
        ]);
    }

    public function testItReturns404ForNonexistentCar(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/cars/1');
    
        $this->assertResponseStatusCodeSame(404);

    }

    public function testCreateCar(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/cars', [
            'headers' => [
                'Content-Type' => "application/ld+json",
                'accept' => "application/ld+json"
            ],
            'json' => [
                'brand' => 'Toyota',
                'model' => 'Camry',
                'color' => 'Blue',
            ]
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Car',
            '@type' => 'Car',
            'brand' => 'Toyota',
            'model' => 'Camry',
            'color' => 'Blue',
            'reviews' => [],
        ]);
    }

    public function testCreateCarInvalidData(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/cars', [
            'headers' => [
                'Content-Type' => "application/ld+json",
                'accept' => "application/ld+json"
            ],
            'json' => [
                'model' => 'Camry',
                'color' => 'Blue',
            ]
        ]);

        $this->assertResponseStatusCodeSame(422);

        $client->request('POST', '/api/cars', [
            'headers' => [
                'Content-Type' => "application/ld+json",
                'accept' => "application/ld+json"
            ],
            'json' => [
                'brand' => 'Toyota',
                'color' => 'Blue',
            ]
        ]);

        $this->assertResponseStatusCodeSame(422);

        $client->request('POST', '/api/cars', [
            'headers' => [
                'Content-Type' => "application/ld+json",
                'accept' => "application/ld+json"
            ],
            'json' => [
                'brand' => 'Toyota',
                'model' => 'Camry',
            ]
        ]);

        $this->assertResponseStatusCodeSame(422);
    }

    public function testUpdateCar(): void
    {
        $car = CarFactory::createOne();
        $client = static::createClient();

        $client->request('PUT', '/api/cars/' . $car->getId(), [
            'headers' => [
                'Content-Type' => "application/ld+json",
                'accept' => "application/ld+json"
            ],
            'json' => [
                'brand' => 'NewBrand',
                'model' => 'NewModel',
                'color' => 'NewColor',
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'id' => $car->getId(),
            'brand' => 'NewBrand',
            'model' => 'NewModel',
            'color' => 'NewColor',
        ]);
    }

    public function testDeleteCar(): void
    {
        $car = CarFactory::createOne();
        $client = static::createClient();
        $id = $car->getId();

        $client->request('DELETE', '/api/cars/' . $car->getId());

        $this->assertResponseStatusCodeSame(204);
        $this->assertNull(
            static::getContainer()->get('doctrine')->getRepository(\App\Entity\Car::class)->find($id)
        );
    }
}
