<?php

namespace App\Form;

use App\Entity\PhpDeveloperRiseRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhpDeveloperRiseRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('approved')
            ->add('createdDate')
            ->add('phpDeveloper')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PhpDeveloperRiseRequest::class,
        ]);
    }
}
