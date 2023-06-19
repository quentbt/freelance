<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MdpOublieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class,[
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Renseignez votre email :',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                    'style' => 'margin-right: 0.5rem;'
                ]
            ])
            ->add('submit', SubmitType::class,[
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label' => 'Envoyer message'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
