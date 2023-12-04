<?php

namespace App\Form;

use App\Entity\Season;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeasonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', IntegerType::class, [
                'attr' => [
                'placeholder' => 'Numéro de saison',
                'class' => 'form-control mb-2 text-primary'
                ],
                'label' => false,
                ])
            ->add('year', IntegerType::class, [
                'attr' => [
                    'placeholder' => 'Année de parution',
                    'class' => 'form-control mb-2 text-primary'
                ],
                'label' => false,
            ])
            ->add('description', TextType::class,[
                'attr' => [
                    'placeholder' => 'description',
                    'class' => 'form-control mb-2 text-primary'
                ],
                'label' => false,
            ])
            ->add('program', null, [
                'choice_label' => 'title',
                'label' => 'Série',

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Season::class,
        ]);
    }
}
