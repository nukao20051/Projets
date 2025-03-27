<?php

namespace App\DataFixtures;

use App\Factory\MedicationFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MedicationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $medications = json_decode(file_get_contents(__DIR__.'/../../data/DataFixtures/Medication.json'), true);
        $imageDirectory = __DIR__.'/../../data/MedicationImages/';
        foreach ($medications as $data) {
            $imagePath = $imageDirectory.$data['image'];
            $imageBlob = file_get_contents($imagePath);
            MedicationFactory::createOne([
                'name' => $data['name'],
                'price' => $data['price'],
                'text' => $data['text'],
                'dosage' => $data['dosage'],
                'unit' => $data['unit'],
                'pharmacyOnly' => $data['pharmacyOnly'],
                'img' => $imageBlob,
            ]);
        }
    }
}
