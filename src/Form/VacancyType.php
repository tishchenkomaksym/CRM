<?php

namespace App\Form;

use App\Entity\Vacancy;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VacancyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('office')
            ->add('department')
            ->add('team')
            ->add('position')
            ->add('amount')
            ->add('requestReason')
            ->add('requirement')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vacancy::class,
        ]);
    }
}
