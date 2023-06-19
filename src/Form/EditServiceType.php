<?php

namespace App\Form;

use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class EditServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Nom :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('prix', NumberType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Prix :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('titre', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Titre :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'height: 10rem'
                ],
                'label' => 'Description :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('imageFile', VichImageType::class,[
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Image :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('secteur',TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Secteur :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            //->add('updatedAt')
            //->add('user')
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ],
                'label' => 'Sauvegarder'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
