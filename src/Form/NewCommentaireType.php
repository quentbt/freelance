<?php

namespace App\Form;

use App\Entity\Commentaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewCommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('commentaire', TextareaType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'height: 10rem; max-width: 40rem',
                    'placeholder' => 'Entrez votre commentaire ici'
                ],
                'label' => 'Commentaire :',
                'label_attr' => [
                    'class' => 'form-label'
                ]
            ])
            ->add('note', ChoiceType::class,[
                'choices' => [
                    '' => '',
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                    '6' => 6,
                    '7' => 7,
                    '8' => 8,
                    '9' => 9,
                    '10' => 10,
                                    ],
                'attr' => [
                    'class' => 'form-select',
                    'style' => 'min-width: 5rem'
                ],
                'label' => 'note :',
                'label_attr' => [
                    'class' => 'form-label'
                ]
            ])
            ->add('user', HiddenType::class)
            ->add('service', HiddenType::class)
            ->add('submit', SubmitType::class,[
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label' => 'enregistrer commentaire'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}
