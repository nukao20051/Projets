<?php

namespace App\DataFixtures;

use App\Factory\MedicalOfficeFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MedicalOfficeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        MedicalOfficeFactory::createMany(10);
    }

    public function getDependencies()
    {
        return [
            AddressFixtures::class,
        ];
    }
}
