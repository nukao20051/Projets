<?php

namespace App\Controller\Admin;

use App\Entity\Medication;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MedicationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Medication::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            IdField::new('price'),
            TextField::new('text'),
            IdField::new('dosage'),
            TextField::new('unit'),
            ChoiceField::new('isPharmacyOnly')
                ->setChoices([
                    'Oui' => '1',
                    'Non' => '0',
                ])
                ->setFormTypeOption('value_type', 'bool'),
        ];
    }
}
