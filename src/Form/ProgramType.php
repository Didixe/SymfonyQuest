<?php

namespace App\Form;

use App\Entity\Program;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Category;
use Doctrine\ORM\Mapping\Entity;


class ProgramType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'placeholder' => 'Titre',
                    'class' => 'form-control mb-2 text-primary'
                ],
                'label' => false,
            ])
            ->add('synopsis', TextType:: class, [
                'attr' => [
                    'placeholder' => 'Synopsis',
                    'class' => 'form-control mb-2 text-primary'
                ],
                'label' => false,
            ])
            ->add('poster', TextType::class, [
                'attr' => [
                    'placeholder' => 'Affiche',
                    'class' => 'form-control mb-2 text-primary'
                ],
                'label' => false,
            ])
//            ->add('category', TextType::class, [
            ->add('category', EntityType::class, [
//                'class' => Entity::class,
                'class' => Category::class,
                'choice_label' => 'name',
                'attr' => [
                    'placeholder' => 'Category',
                    'class' => 'form-control mb-2 text-primary'
                ],
                'label' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Program::class,
        ]);
    }
}
