<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Repository\AddressRepository;
use App\Repository\PersonRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $address = function ($value): string {
            return $value?->getNum().' '.$value->getStreet().' '.$value->getCity();
        };
        $person = function ($value): string {
            return $value?->getFirstName().' '.$value->getLastname();
        };

        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('person')->setFormTypeOptions(['choice_label' => $person, 'query_builder' => function (PersonRepository $person) {
                return $person->createQueryBuilder('p')
                    ->orderBy('p.firstname', 'ASC');
            }, ])->formatValue($person),
            AssociationField::new('address')->setFormTypeOptions(['choice_label' => $address, 'query_builder' => function (AddressRepository $adress) {
                return $adress->createQueryBuilder('a')
                    ->orderBy('a.num', 'ASC');
            }, ])->formatValue($address),
            DateField::new('delivery_date'),
            TextField::new('order_state'),
            ChoiceField::new('payement')->setChoices([
                'Visa' => 'visa',
                'Mastercard' => 'mastercard',
                'Paypal' => 'paypal',
                'Revolut' => 'revolut',
                'Paysafecard' => 'paysafecard',
            ]),
        ];
    }
}
