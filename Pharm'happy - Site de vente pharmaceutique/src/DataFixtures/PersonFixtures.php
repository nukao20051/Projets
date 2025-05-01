<?php

namespace App\DataFixtures;

use App\Factory\AddressFactory;
use App\Factory\MedicalOfficeFactory;
use App\Factory\PersonFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PersonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $address = AddressFactory::new()->create();
        $addressReal = $address->getNum().' '.$address->getStreet().', '.$address->getCity().' ('.$address->getPc().')';
        PersonFactory::createSequence([
            [
                'firstname' => 'User',
                'lastname' => 'One',
                'email' => 'user@example.com',
            ],
            [
                'firstname' => 'Admin',
                'lastname' => 'Two',
                'email' => 'admin@example.com',
                'roles' => ['ROLE_ADMIN'],
            ],
            [
                'firstname' => 'Manager',
                'lastname' => 'Three',
                'email' => 'manager@example.com',
                'roles' => ['ROLE_MANAGER'],
            ],
            [
                'firstname' => 'UserMedicalOffice',
                'lastname' => 'Four',
                'email' => 'user-mo@example.com',
                'address' => [$address],
                'medicaloffice' => MedicalOfficeFactory::new([
                    'location' => $addressReal,
                ]),
                'roles' => ['ROLE_PHARMACY'],
            ]],
        );
        PersonFactory::createMany(5);
    }

    public function getDependencies(): array
    {
        return [MedicalOfficeFixtures::class];
    }
}
