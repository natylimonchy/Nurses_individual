<?php

namespace App\Form;

use App\Entity\Nurse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NurseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('internalId')
            ->add('firstName')
            ->add('lastName')
            ->add('licenseNumber')
            ->add('username')
            ->add('password')
            ->add('email')
            ->add('shift')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Nurse::class,
        ]);
    }
}
