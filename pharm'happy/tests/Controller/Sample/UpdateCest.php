<?php

namespace App\Tests\Controller\Sample;

use App\Factory\MedicationFactory;
use App\Factory\OrderFactory;
use App\Factory\PersonFactory;
use App\Factory\SampleFactory;
use App\Tests\Support\ControllerTester;

class UpdateCest
{
    public function formShowsSampleDataBeforeUpdating(ControllerTester $I): void
    {
        $user = PersonFactory::createOne([
            'roles' => ['ROLE_ADMIN'],
        ])->_real();
        $I->amLoggedInAs($user);
        MedicationFactory::createOne();
        OrderFactory::createOne();
        $sample = SampleFactory::createOne();
        $I->amOnPage('sample/'.$sample->getId().'/update');
        $I->seeInTitle('Echantillon #'.$sample->getId(), 'h1');
    }
}
