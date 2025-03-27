<?php

namespace App\Tests\Controller\Sample;

use App\Factory\MedicationFactory;
use App\Factory\PersonFactory;
use App\Factory\SampleFactory;
use App\Tests\Support\ControllerTester;

class IndexCest
{
    public function testSampleControllerIndex(ControllerTester $I): void
    {
        $user = PersonFactory::createOne([
            'roles' => ['ROLE_ADMIN'],
        ])->_real();
        $I->amLoggedInAs($user);
        $medication = MedicationFactory::createOne();
        SampleFactory::createMany(12, ['expiration' => new \DateTime('+1 year'), 'medication' => $medication]);
        $I->amOnPage('/sample/1');
        $I->seeResponseCodeIs(200);
        $I->seeInTitle('Liste des échantillons');
        $I->seeNumberOfElements('.sample', 12);
    }
}
