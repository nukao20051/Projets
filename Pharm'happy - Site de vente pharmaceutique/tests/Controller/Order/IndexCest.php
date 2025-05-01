<?php

namespace App\Tests\Controller\Order;

use App\Factory\MedicationFactory;
use App\Factory\OrderFactory;
use App\Factory\PersonFactory;
use App\Tests\Support\ControllerTester;

class IndexCest
{
    public function accessIsRestrictedToLoggedOnly(ControllerTester $I): void
    {
        $I->amOnPage('/order');
        $I->seeCurrentRouteIs('app_login');
    }

    public function allOrdersAreDisplayedForAdmin(ControllerTester $I): void
    {
        $user = PersonFactory::createOne([
            'roles' => ['ROLE_ADMIN'],
        ])->_real();
        $I->amLoggedInAs($user);

        MedicationFactory::createMany(10);
        OrderFactory::createMany(10);
        $I->amOnPage('/order');
        $I->seeNumberOfElements('.card', 10);
    }

    public function usersCanSeeOnlyTheirOrders(ControllerTester $I): void
    {
        $user = PersonFactory::createOne([
            'roles' => ['ROLE_USER'],
        ])->_real();
        $I->amLoggedInAs($user);
        MedicationFactory::createMany(10);
        OrderFactory::createMany(10);
        OrderFactory::createMany(4, ['person' => $user]);

        $I->amOnPage('/order');
        $I->seeNumberOfElements('.card', 4);
    }

    public function testClickOnOrder(ControllerTester $I): void
    {
        $user = PersonFactory::createOne([
            'roles' => ['ROLE_ADMIN'],
        ])->_real();
        $I->amLoggedInAs($user);

        MedicationFactory::createMany(10);
        OrderFactory::createOne();
        $I->amOnPage('/order');
        $I->click('Voir les détails');
        $I->seeResponseCodeIsSuccessful();
        $I->seeCurrentUrlEquals('/order/1');
    }
}
