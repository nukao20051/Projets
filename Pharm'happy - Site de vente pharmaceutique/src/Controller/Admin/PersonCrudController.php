<?php

namespace App\Controller\Admin;

use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PersonCrudController extends AbstractCrudController
{
    private UserPasswordHasherInterface $userPasswordHasherInterface;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->userPasswordHasherInterface = $passwordHasher;
    }

    public static function getEntityFqcn(): string
    {
        return Person::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('lastname'),
            TextField::new('firstname'),
            DateField::new('birth_dat'),
            TextField::new('phone'),
            EmailField::new('email'),
            ArrayField::new('roles'),
            AssociationField::new('medicalOffice', 'MedicalOffice')
                ->setFormTypeOption('choice_label', 'name'),
            TextField::new('password')
                ->setFormType(PasswordType::class)
                ->setFormTypeOptions([
                    'required' => false,
                    'empty_data' => '',
                    'attr' => ['autocomplete' => 'new-password'],
                ])->onlyOnForms(),
        ];
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        parent::updateEntity($entityManager, $entityInstance);
        $request = $this->getContext()->getRequest();
        $password = $request->get('Person')['password'];
        $this->setUserPassword($password, $entityInstance, $entityManager);
        $entityManager->flush();
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        parent::persistEntity($entityManager, $entityInstance);
        $request = $this->getContext()->getRequest();
        $password = $request->get('Person')['password'];
        $this->setUserPassword($password, $entityInstance, $entityManager);
        $entityManager->flush();
    }

    public function setUserPassword(?string $password, $entityInstance, EntityManagerInterface $entityManager): void
    {
        if ('' != $password) {
            $entityInstance->setPassword($this->userPasswordHasherInterface->hashPassword($entityInstance, $password));
        }
    }
}
