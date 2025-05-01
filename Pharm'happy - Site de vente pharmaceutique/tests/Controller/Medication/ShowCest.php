<?php

namespace App\Tests\Controller\Medication;

use App\Factory\MedicationFactory;
use App\Factory\PersonFactory;
use App\Factory\SampleFactory;
use App\Tests\Support\ControllerTester;

class ShowCest
{
    public function contentIsCorrect(ControllerTester $I): void
    {
        $medication = MedicationFactory::createOne();
        $I->amOnPage('/medication/1');
        $I->seeResponseCodeIs(200);
        $I->see($medication->getName(), 'li.name');
        $I->see($medication->getText(), 'li.description');
        $I->see(number_format($medication->getPrice(), 2, ',', ''), 'li.price');
        $I->see($medication->getDosage(), 'li.dosage');
    }

    public function formIsCorrectForUser(ControllerTester $I): void
    {
        $medication = MedicationFactory::createOne([
            'name' => 'Paracetamol',
        ]);
        SampleFactory::createOne(['expiration' => new \DateTime('+1 year'), 'medication' => $medication]);
        $user = PersonFactory::createOne([
            'roles' => ['ROLE_USER'],
        ])->_real();

        $I->amLoggedInAs($user);

        $I->amOnPage('/medication/1');
        $I->see('Ajouter au panier', 'form');
    }

    public function formIsCorrectForAnyone(ControllerTester $I): void
    {
        $medication = MedicationFactory::createOne([
            'name' => 'Paracetamol',
        ]);
        SampleFactory::createOne(['expiration' => new \DateTime('+1 year'), 'medication' => $medication]);

        $I->amOnPage('/medication/1');
        $I->see('Ajouter au panier', 'form');
    }

    public function formIsCorrectForAdmin(ControllerTester $I): void
    {
        MedicationFactory::createOne([
            'name' => 'Paracetamol',
        ]);

        $user = PersonFactory::createOne([
            'roles' => ['ROLE_ADMIN'],
        ])->_real();

        $I->amLoggedInAs($user);

        $I->amOnPage('/medication/1');
        $I->see('Modifier', 'form');
        $I->see('Supprimer', 'form');
    }
}
