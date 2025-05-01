<?php

namespace App\Tests\Controller\Order;

use App\Factory\MedicationFactory;
use App\Factory\OrderFactory;
use App\Factory\PersonFactory;
use App\Tests\Support\ControllerTester;

class ShowCest
{
    public function testShowOrder(ControllerTester $I): void
    {
        $user = PersonFactory::createOne([
            'roles' => ['ROLE_ADMIN'],
        ])->_real();
        $I->amLoggedInAs($user);

        MedicationFactory::createMany(10);
        $order = OrderFactory::createOne(['person' => $user]);
        $I->amOnPage('/order/'.$order->getId());
        $I->seeResponseCodeIs(200);
        $I->see('Détail de la commande n°'.$order->getId());
    }

    public function testOrderDeliveryDetails(ControllerTester $I): void
    {
        $user = PersonFactory::createOne([
            'roles' => ['ROLE_ADMIN'],
        ])->_real();
        $I->amLoggedInAs($user);
        MedicationFactory::createMany(10);
        $order = OrderFactory::createOne(['person' => $user]);
        $I->amOnPage('/order/'.$order->getId());
        $I->seeResponseCodeIs(200);
        $I->see('Livraison le '.$order->getDeliveryDate()->format('d/m/Y'));
        $I->see($order->getAddress()->getNum().' '.$order->getAddress()->getStreet());
        $I->see($order->getAddress()->getCity());
        $I->see('('.$order->getAddress()->getPc().')');
    }
}
