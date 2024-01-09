<?php

namespace App\DataFixtures;

use App\Factory\CarFactory;
use App\Factory\ReviewFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        ReviewFactory::createMany(30);
        CarFactory::createMany(10);

    }
}
