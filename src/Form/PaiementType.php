<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
class PaiementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numCard', TextType::class,[
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Numéro de carte :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Length(16),
                    new Assert\CardScheme('VISA'),
                    new Assert\CardScheme('MASTERCARD'),
                    new Assert\Luhn(),
                ]
            ])
            ->add('titulaire', TextType::class,[
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Titulaire de la carte :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('dateExpiration', DateType::class,[
                'attr' => [
                    'class' => 'tinymce'
                ],
                'label' => 'Date Expiration :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Date() > time(),
                ]
            ])
            ->add('CVV', TextType::class,[
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'CVV :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Length(3),
                    new Assert\Range(min(000),max(999),'Vôtre code CVV n\'est pas bon')
                ]
            ])
            ->add('submit', SubmitType::class,[
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ],
                'label' => 'Acheter',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
