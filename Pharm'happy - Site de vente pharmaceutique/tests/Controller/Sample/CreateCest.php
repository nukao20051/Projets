<?php

namespace App\Tests\Controller\Sample;

use App\Factory\PersonFactory;
use App\Tests\Support\ControllerTester;

class CreateCest
{
    public function showCreateForm(ControllerTester $I): void
    {
        $user = PersonFactory::createOne([
            'roles' => ['ROLE_ADMIN'],
        ])->_real();
        $I->amLoggedInAs($user);
        $I->amOnPage('sample/create');
        $I->seeResponseCodeIs('200');
        $I->see('Ajout d\'un échantillon', 'h1');
    }
}
