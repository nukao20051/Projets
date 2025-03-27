<?php

namespace App\Tests\Controller\Medication;

use App\Factory\MedicationFactory;
use App\Factory\SampleFactory;
use App\Tests\Support\ControllerTester;

class IndexCest
{
    public function testResponseHTTP(ControllerTester $I): void
    {
        $I->amOnPage('/medication');
        $I->canSeeResponseCodeIsSuccessful();
    }

    public function titleCorrectlySet(ControllerTester $I): void
    {
        $I->amOnPage('/medication');
        $I->seeInTitle("Pharm'Happy");
    }

    public function allMedicationsAreDisplayed(ControllerTester $I): void
    {
        MedicationFactory::createMany(5);
        $I->amOnPage('/medication');
        $I->canSeeResponseCodeIsSuccessful();
        $I->seeNumberOfElements('.card', 5);
    }

    public function firstMedicationIsLinkedToTheGoodPath(ControllerTester $I): void
    {
        $medication = MedicationFactory::createOne(['name' => 'AAAAAAAAAAAAAAAAAAAAA']); // this name just to make sure that it's the first one if alphabetical sorting
        MedicationFactory::createMany(5);
        $I->amOnPage('/medication');
        $I->click('Voir Détails');
        $I->seeResponseCodeIsSuccessful();
        $I->seeCurrentUrlEquals('/medication/1');
    }

    public function medicationAreSorted(ControllerTester $I): void
    {
        MedicationFactory::createSequence(
            [
                ['name' => 'Antihistaminique'],
                ['name' => 'Paracetamol'],
                ['name' => 'Alprazolam'],
            ]
        );
        $I->amOnPage('/medication');
        $medications = $I->grabMultiple('.card-title');
        $sortedMedications = ['Alprazolam', 'Antihistaminique', 'Paracetamol'];
        $I->assertEquals($medications, $sortedMedications);
    }

    public function search(ControllerTester $I): void
    {
        MedicationFactory::createSequence(
            [
                ['name' => 'Antihistaminique'],
                ['name' => 'Paracetamol'],
                ['name' => 'Alprazolam'],
            ]
        );
        $I->amOnPage('/medication?search=ol');
        $I->seeNumberOfElements('.card', 2);
    }

    public function sortedByStockAsc(ControllerTester $I): void
    {
        $med1 = MedicationFactory::createOne(['name' => 'Antihistaminique']);
        $med2 = MedicationFactory::createOne(['name' => 'Paracetamol']);
        $med3 = MedicationFactory::createOne(['name' => 'Alprazolam']);
        for ($i = 0; $i < 9; ++$i) {
            SampleFactory::createOne(['medication' => $med1]);
        }
        for ($i = 0; $i < 2; ++$i) {
            SampleFactory::createOne(['medication' => $med2]);
        }
        for ($i = 0; $i < 8; ++$i) {
            SampleFactory::createOne(['medication' => $med3]);
        }
        $I->amOnPage('/medication?filter=s-asc');
        $I->seeNumberOfElements('.card', 3);
        $I->click('Voir Détails');
        $I->seeResponseCodeIsSuccessful();
        $I->seeCurrentUrlEquals('/medication/2');
    }

    public function sortedByStockDesc(ControllerTester $I): void
    {
        $med1 = MedicationFactory::createOne(['name' => 'Antihistaminique']);
        $med2 = MedicationFactory::createOne(['name' => 'Paracetamol']);
        $med3 = MedicationFactory::createOne(['name' => 'Alprazolam']);
        for ($i = 0; $i < 5; ++$i) {
            SampleFactory::createOne(['medication' => $med1]);
        }
        for ($i = 0; $i < 15; ++$i) {
            SampleFactory::createOne(['medication' => $med2]);
        }
        for ($i = 0; $i < 8; ++$i) {
            SampleFactory::createOne(['medication' => $med3]);
        }
        $I->amOnPage('/medication?filter=s-desc');
        $I->seeNumberOfElements('.card', 3);
        $I->click('Voir Détails');
        $I->seeResponseCodeIsSuccessful();
        $I->seeCurrentUrlEquals('/medication/2');
    }

    public function sortedByPriceAsc(ControllerTester $I): void
    {
        MedicationFactory::createOne(['name' => 'Antihistaminique', 'price' => 100]);
        MedicationFactory::createOne(['name' => 'Paracetamol', 'price' => 4]);
        MedicationFactory::createOne(['name' => 'Alprazolam', 'price' => 300]);
        $I->amOnPage('/medication?filter=p-asc');
        $I->seeNumberOfElements('.card', 3);
        $I->click('Voir Détails');
        $I->seeResponseCodeIsSuccessful();
        $I->seeCurrentUrlEquals('/medication/2');
    }

    public function sortedByPriceDesc(ControllerTester $I): void
    {
        MedicationFactory::createOne(['name' => 'Antihistaminique', 'price' => 100]);
        MedicationFactory::createOne(['name' => 'Paracetamol', 'price' => 1000]);
        MedicationFactory::createOne(['name' => 'Alprazolam', 'price' => 300]);
        $I->amOnPage('/medication?filter=p-desc');
        $I->seeNumberOfElements('.card', 3);
        $I->click('Voir Détails');
        $I->seeResponseCodeIsSuccessful();
        $I->seeCurrentUrlEquals('/medication/2');
    }

    public function addToCartNoStock(ControllerTester $I): void
    {
        MedicationFactory::createOne();
        $I->amOnPage('/medication');
        $I->dontSeeElement('add_shopping_cart');
    }

    public function addToCartWithStock(ControllerTester $I): void
    {
        $medication = MedicationFactory::createOne();
        SampleFactory::createOne(['medication' => $medication, 'expiration' => new \DateTime('+1 year')]);
        $I->amOnPage('/medication');
        $I->click('add_shopping_cart');
        $I->seeResponseCodeIsSuccessful();
        $I->amOnPage('/cart');
        $I->seeNumberOfElements('.med', 1);
    }
}
