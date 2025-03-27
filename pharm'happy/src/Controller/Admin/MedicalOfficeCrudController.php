<?php

namespace App\Controller\Admin;

use App\Entity\MedicalOffice;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MedicalOfficeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MedicalOffice::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $address = function ($value): string {
            return $value?->getNum().' '.$value->getStreet().' '.$value->getCity();
        };

        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('Name'),
            TextField::new('Phone'),
            TextField::new('location'),
        ];
    }
}
