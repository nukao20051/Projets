<?php

namespace App\Form;

use App\Entity\Medication;
use App\Entity\Order;
use App\Entity\Sample;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SampleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('expiration', DateType::class)
            ->add('medication', EntityType::class, [
                'class' => Medication::class,
                'choice_label' => 'name',
                'required' => true,
                'query_builder' => function (EntityRepository $entityRepository) {
                    return $entityRepository->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
            ])
        ->add('order', EntityType::class, [
            'class' => Order::class,
            'required' => false,
            'choice_label' => 'id',
        ]);
        if (isset($options['action']) && 'create' === $options['action']) {
            $builder->add('repeatCount', IntegerType::class, [
                'label' => 'Nombre de copies',
                'mapped' => false,
                'attr' => ['min' => 1, 'value' => 1],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sample::class,
        ]);
    }
}
