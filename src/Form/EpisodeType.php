<?php

namespace App\Form;

use App\Entity\Episode;
use App\Entity\Season;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EpisodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                'placeholder' => 'Titre',
                'class' => 'form-control mb-2 text-primary'
                ],

            ])
            ->add('number', IntegerType::class, [
                'attr' => [
                    'placeholder' => 'N°',
                    'class' => 'form-control mb-2 text-primary'
                ],
            ])
            ->add('synopsis', TextType::class, [
                'attr' => [
                    'placeholder' => 'synopsis',
                    'class' => 'form-control mb-2 text-primary'
                ],

            ])
            ->add('duration', IntegerType::class, [
                'attr' => [
                    'placeholder' => 'durée',
                    'class' => 'form-control mb-2 text-primary'
                ],
            ])
            ->add('season', EntityType::class, [
                'class' => Season::class,
                'choice_label' => function ($season) {
                    return $season->getProgram()->getTitle() . ' - Season ' . $season->getNumber();
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Episode::class,
        ]);
    }
}
