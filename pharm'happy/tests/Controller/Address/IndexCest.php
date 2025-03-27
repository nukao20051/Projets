<?php

namespace App\Tests\Controller\Address;

use App\Factory\AddressFactory;
use App\Factory\PersonFactory;
use App\Tests\Support\ControllerTester;

class IndexCest
{
    public function allAddressesAreDisplayedForUsers(ControllerTester $I): void
    {
        $user1 = PersonFactory::createOne([
            'roles' => ['ROLE_USER'],
        ])->_real();
        $user2 = PersonFactory::createOne([
            'roles' => ['ROLE_USER'],
        ])->_real();
        AddressFactory::createMany(8, ['person' => $user1]);
        AddressFactory::createMany(13, ['person' => $user2]);

        $I->amLoggedInAs($user1);
        $I->amOnPage('/address');
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('.address', 8);

        $I->amLoggedInAs($user2);
        $I->amOnPage('/address');
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('.address', 13);
    }

    public function accessIsRestrictedToLoggedOnly(ControllerTester $I): void
    {
        $I->amOnPage('/address');
        $I->seeCurrentRouteIs('app_login');
    }
}
