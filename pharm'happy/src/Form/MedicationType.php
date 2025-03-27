<?php

namespace App\Form;

use App\Entity\Medication;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MedicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('text', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dosage', NumberType::class, [
                'label' => 'Dosage',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('unit', TextType::class, [
                'label' => 'Unité',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('img', FileType::class, [
                'label' => 'Image du médicament',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Medication::class,
        ]);
    }
}
