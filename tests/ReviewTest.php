<?php 

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Factory\CarFactory;
use App\Factory\ReviewFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class ReviewTest extends ApiTestCase
{
    use ResetDatabase, Factories;

    public function testCreateReview(): void
    {
        $client = self::createClient();

        $car = \App\Factory\CarFactory::createOne();

        $client->request('POST', '/api/reviews', [
            'json' => [
                'car' => '/api/cars/'.$car->getId(),
                'starrating' => 5,
                'reviewtext' => 'Test Review',
            ],
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json',
            ],
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/api/contexts/Review',
            '@type' => 'Review',
            'starrating' => 5,
            'reviewtext' => 'Test Review'
        ]);
    }

    public function testGetReviewsForCar(): void
    {
        $client = self::createClient();
        $car = CarFactory::createOne();
        ReviewFactory::createMany(3, ['car' => $car]);

        $client->request('GET', '/api/cars/'.$car->getId().'/reviews', [
            'headers' => [
                'accept' => 'application/ld+json',
            ],
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $responseData = $client->getResponse()->toArray();
        $this->assertArrayHasKey('@context', $responseData);
        $this->assertArrayHasKey('@type', $responseData);
        $this->assertArrayHasKey('@id', $responseData);
        $this->assertArrayHasKey('hydra:totalItems', $responseData);
        $this->assertArrayHasKey('hydra:member', $responseData);

        $this->assertCount(3, $responseData['hydra:member']);
    }

    public function testUpdateReview(): void
    {
        $client = self::createClient();
        $car = CarFactory::createOne();

        $review = ReviewFactory::createOne(['car' => $car]);

        $client->request('PUT', '/api/cars/' . $car->getId() . '/reviews/'.$review->getId(), [
            'json' => [
                'starrating' => 4,
                'reviewtext' => 'Updated Review',
            ],
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json',
            ],
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $responseData = $client->getResponse()->toArray();
        $this->assertArrayHasKey('@context', $responseData);
        $this->assertArrayHasKey('@type', $responseData);
        $this->assertArrayHasKey('@id', $responseData);
        $this->assertArrayHasKey('car', $responseData);
        $this->assertArrayHasKey('starrating', $responseData);
        $this->assertArrayHasKey('reviewtext', $responseData);

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $updatedReview = $entityManager->getRepository(\App\Entity\Review::class)->find($review->getId());
        $this->assertInstanceOf(\App\Entity\Review::class, $updatedReview);
    }

    public function testDeleteReview(): void
    {
        $client = self::createClient();
        $car = CarFactory::createOne();

        $review = ReviewFactory::createOne(['car' => $car]);
        $id = $review->getId();

        $client->request('DELETE', '/api/cars/' . $car->getId() . '/reviews/' . $id, [
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json',
            ],
        ]);

        $this->assertResponseStatusCodeSame(204);

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $deletedReview = $entityManager->getRepository(\App\Entity\Review::class)->find($id);
        $this->assertNull($deletedReview);
    }

    public function testGetLatestReviewsForCar(): void
    {
        $client = self::createClient();

        $car = CarFactory::createOne();
        ReviewFactory::createMany(5, ['car' => $car]);


        $client->request('GET', '/api/cars/'.$car->getId().'/latest-reviews', [
            'query' => [
                'limit' => 3,
                'min_rating' => 4,
            ],
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json',
            ],
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $responseData = $client->getResponse()->toArray();
        $this->assertArrayHasKey('@context', $responseData);
        $this->assertArrayHasKey('@type', $responseData);
        $this->assertArrayHasKey('@id', $responseData);
        $this->assertArrayHasKey('hydra:totalItems', $responseData);
        $this->assertArrayHasKey('hydra:member', $responseData);

        $this->assertCount(3, $responseData['hydra:member']);

        foreach ($responseData['hydra:member'] as $review) {
            $this->assertGreaterThanOrEqual(4, $review['starrating']);
        }
    }
}
