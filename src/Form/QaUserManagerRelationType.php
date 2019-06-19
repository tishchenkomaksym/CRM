<?php

namespace App\Form;

use App\Entity\QaUserManagerRelation;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QaUserManagerRelationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'qaManager',
                EntityType::class,
                [
                    'class' => User::class,
                    'choice_label' => 'email',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => QaUserManagerRelation::class,
            ]
        );
    }
}
