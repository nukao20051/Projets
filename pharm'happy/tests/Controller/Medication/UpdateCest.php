<?php

namespace App\Tests\Controller\Medication;

use App\Factory\MedicationFactory;
use App\Factory\PersonFactory;
use App\Tests\Support\ControllerTester;
use Codeception\Util\HttpCode;

class UpdateCest
{
    public function formShowsMedicationDataBeforeUpdating(ControllerTester $I): void
    {
        MedicationFactory::createOne([
            'name' => 'Paracetamol',
        ]);

        $user = PersonFactory::createOne([
            'roles' => ['ROLE_ADMIN'],
        ])->_real();

        $I->amLoggedInAs($user);

        $I->amOnPage('/medication/1/update');

        $I->seeInTitle('Modification de médicament');
        $I->see('Modification du médicament : Paracetamol', 'h1');
    }

    public function accessIsRestrictedToAuthenticatedUsers(ControllerTester $I): void
    {
        MedicationFactory::createOne();
        $I->amOnPage('/medication/1/update');
        $I->seeInCurrentUrl('/login');
    }

    public function accessIsRestrictedToAdminUsers(ControllerTester $I): void
    {
        MedicationFactory::createOne();
        $user = PersonFactory::createOne([
            'roles' => ['ROLE_USER'],
        ])->_real();

        $I->amLoggedInAs($user);
        $I->amOnPage('/medication/1/update');
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }
}
