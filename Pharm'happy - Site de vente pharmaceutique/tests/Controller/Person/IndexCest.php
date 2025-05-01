<?php

namespace App\Tests\Controller\Person;

use App\Factory\PersonFactory;
use App\Tests\Support\ControllerTester;
use Codeception\Util\HttpCode;

class IndexCest
{
    public function pageIsAccessibleOnlyForAdmins(ControllerTester $I): void
    {
        $I->amOnPage('/person');
        $I->seeCurrentRouteIs('app_login');

        $user = PersonFactory::createOne([
            'roles' => ['ROLE_ADMIN'],
        ])->_real();
        $I->amLoggedInAs($user);
        $I->amOnPage('/person');
        $I->canSeeResponseCodeIsSuccessful();
    }

    public function allPersonsAreDisplayed(ControllerTester $I): void
    {
        $user = PersonFactory::createOne([
            'roles' => ['ROLE_ADMIN'],
        ])->_real();
        $I->amLoggedInAs($user);

        PersonFactory::createMany(5);
        $I->amOnPage('/person');
        $I->seeNumberOfElements('.card', 6);
    }

    public function firstPersonIsLinkedToTheGoodPath(ControllerTester $I): void
    {
        $user = PersonFactory::createOne([
            'roles' => ['ROLE_ADMIN'],
        ])->_real();
        $I->amLoggedInAs($user);

        $I->amOnPage('/person');
        $I->click('Voir les détails');
        $I->seeResponseCodeIsSuccessful();
        $I->seeCurrentUrlEquals('/person/1');
    }

    public function personCanOnlyAccessToTheirProfile(ControllerTester $I): void
    {
        $user1 = PersonFactory::createOne([
            'roles' => ['ROLE_USER'],
        ])->_real();
        $I->amLoggedInAs($user1);

        $user2 = PersonFactory::createOne();

        $I->amOnPage('/person/'.$user1->getId());
        $I->seeResponseCodeIsSuccessful();

        $I->amOnPage('/person/'.$user2->getId());
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }
}
