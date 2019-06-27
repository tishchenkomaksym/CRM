<?php

namespace App\Form;

use App\Entity\DepartmentTeamSdtViewRules;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepartmentTeamSdtViewRulesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('department', ChoiceType::class, [
                'multiple' => true,
                'expanded' => true,
                'choices' => $this->roles,
            ])
            ->add('team', ChoiceType::class, [
                'multiple' => true,
                'expanded' => true,
                'choices' => $this->roles,
    ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => DepartmentTeamSdtViewRules::class,
            ]
        );
    }
}
