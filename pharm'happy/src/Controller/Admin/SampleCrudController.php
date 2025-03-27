<?php

namespace App\Controller\Admin;

use App\Entity\Sample;
use App\Repository\MedicationRepository;
use App\Repository\OrderRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class SampleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Sample::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('medication')->setFormTypeOptions(['choice_label' => 'name', 'query_builder' => function (MedicationRepository $medications) {
                return $medications->createQueryBuilder('m')
                    ->orderBy('m.name', 'ASC');
            }, ])->formatValue(function ($value) {
                return $value?->getName();
            }),
            AssociationField::new('order')->setFormTypeOptions(['choice_label' => 'id', 'query_builder' => function (OrderRepository $orders) {
                return $orders->createQueryBuilder('o')
                    ->orderBy('o.id', 'ASC');
            }, ]),
            DateField::new('expiration'),
        ];
    }
}
