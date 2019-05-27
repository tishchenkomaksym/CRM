<?php

namespace App\Form;

use App\Entity\PhpDeveloperLevel;
use App\Entity\UserPhpDeveloperLevelRelation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserPhpDeveloperLevelRelationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'phpDeveloperLevel',
                EntityType::class,
                [
                    'class' => PhpDeveloperLevel::class,
                    'choice_label' => 'title',
//                    'choices' => $choices
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => UserPhpDeveloperLevelRelation::class,
            ]
        );
    }
}
