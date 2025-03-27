<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('num', NumberType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le numéro ne peut pas être vide.']),
                    new Assert\Positive(['message' => 'Le numéro doit être un nombre positif.']),
                ],
                'trim' => true,
            ])
            ->add('street', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La rue ne peut pas être vide.']),
                    new Assert\Length([
                        'max' => 255,
                        'maxMessage' => 'La rue doit comporter moins de {{ limit }} caractères.',
                    ]),
                ],
                'trim' => true,
            ])
            ->add('city', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La ville ne peut pas être vide.']),
                    new Assert\Length([
                        'max' => 255,
                        'maxMessage' => 'La ville doit comporter moins de {{ limit }} caractères.',
                    ]),
                ],
                'trim' => true,
            ])
            ->add('pc', NumberType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le code postal ne peut pas être vide.']),
                    new Assert\Positive(['message' => 'Le code postal doit être un nombre positif.']),
                    new Assert\Length([
                        'min' => 4,
                        'max' => 8,
                        'minMessage' => 'Le code postal doit comporter au moins {{ limit }} chiffres.',
                        'maxMessage' => 'Le code postal ne doit pas dépasser {{ limit }} chiffres.',
                    ]),
                ],
                'trim' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
