<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Order;
use App\Repository\AddressRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class OrderType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $person_id = $this->security->getUser();
        $builder
            ->add('payement', ChoiceType::class, [
                'label' => 'Moyen de paiement',
                'multiple' => false,
                'choices' => [
                    'Visa' => 'visa',
                    'MasterCard' => 'mastercard',
                    'Paypal' => 'paypal',
                    'Revolut' => 'revolut',
                    'Paysafecard' => 'paysafecard',
                ],
                'required' => true,
            ])
            ->add('address', EntityType::class, [
                'class' => Address::class,
                'label' => 'Adresse',
                'choice_label' => function (Address $address) {
                    return $address->getNum().' '.$address->getStreet().', '.$address->getCity().' ('.$address->getPc().')';
                },
                'required' => true,
                'query_builder' => function (AddressRepository $addressRepository) use ($person_id) {
                    return $addressRepository->createQueryBuilder('a')
                        ->where('a.person = :person_id')
                        ->setParameter('person_id', $person_id);
                },
                'constraints' => [new Assert\NotBlank(['message' => 'L\'adresse est obligatoire.'])],
            ],
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
