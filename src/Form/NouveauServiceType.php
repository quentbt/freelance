<?php

namespace App\Form;

use App\Entity\Service;
use PHPUnit\Framework\Constraint\GreaterThan;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints as Assert;

class NouveauServiceType extends AbstractType
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
                    'class' => 'label-form mt-4'
                ]
            ])
            ->add('prix', MoneyType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Prix :',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                    'style' => 'margin-right: 0.5rem;'
                ],
                'constraints' => [
                ]
            ])
            ->add('titre', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Titre :',
                'label_attr' => [
                    'class' => 'label-form mt-4'
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
            ->add('secteur', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Secteur :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('imageFile', VichImageType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Image',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('user', HiddenType::class)
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label' => 'Enregistrer nouveau service',
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
