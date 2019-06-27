<?php

namespace App\Form\QaAgentProfile;

use App\Entity\PhpDeveloperLevelTestPassed;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QaAgentTechnicalSkillsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('skillTitle')
            ->add('actualPoints')
            ->add('requiredPoints')
            ->add('testLink')
            ->add('testResult')
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
                                   'data_class' => PhpDeveloperLevelTestPassed::class,
                               ]
        );
    }
}
