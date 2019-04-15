<?php

namespace App\Form;

use App\Entity\PhpDeveloperStartTimeAndDateValue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhpDeveloperStartTimeAndDateValueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('effectiveTime')
            ->add('effectiveProjectTime')
            ->add('createDate')
            ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PhpDeveloperStartTimeAndDateValue::class,
        ]);
    }
}
