<?php

namespace App\Tests\Controller\Medication;

use App\Factory\PersonFactory;
use App\Tests\Support\ControllerTester;
use Codeception\Util\HttpCode;

class CreateCest
{
    public function accessIsRestrictedToAuthenticatedUsers(ControllerTester $I): void
    {
        $I->amOnPage('/medication/create');
        $I->seeInCurrentUrl('/login');
    }

    public function accessIsRestrictedToAdminUsers(ControllerTester $I): void
    {
        $user = PersonFactory::createOne([
            'roles' => ['ROLE_USER'],
        ])->_real();

        $I->amLoggedInAs($user);
        $I->amOnPage('/medication/create');
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }
}
