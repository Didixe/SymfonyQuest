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
use App\Entity\Actor;
use Vich\UploaderBundle\Form\Type\VichFileType;
use function Sodium\add;


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

            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'attr' => [
                    'placeholder' => 'Category',
                    'class' => 'form-control mb-2 text-primary'
                ],
                'label' => false,
            ])
            ->add('actors', EntityType::class, [
                'class' => Actor::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
            ])
            ->add('posterFile', VichFileType::class, [
                'required' => false,
                'allow_delete' => true,
                'download_uri' => true,
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
