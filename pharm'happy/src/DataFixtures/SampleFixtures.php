<?php

namespace App\DataFixtures;

use App\Factory\SampleFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SampleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        SampleFactory::createMany(200);
    }

    public function getDependencies(): array
    {
        return [
            MedicationFixtures::class,
        ];
    }
}
