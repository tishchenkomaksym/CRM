<?php

namespace App\Form;

use App\Entity\PhpDeveloperLevelTestPassed;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhpDeveloperLevelTestPassedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user')
            ->add('phpDeveloperLevelTest')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PhpDeveloperLevelTestPassed::class,
        ]);
    }
}
