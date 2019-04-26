<?php

namespace App\Form;

use App\Entity\Team;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'email',
                TextType::class
            )
            ->add(
                'name',
                TextType::class
            )
            ->add(
                'createDate'
            )
            ->add(
                'team',
                EntityType::class,
                [
                    'class' => Team::class,
                    'choice_label' => function (Team $entity) {
                        return $entity->getDepartment()->getName() . ' - ' . $entity->getName();
                    },
//                    'choices' => $choices
                ]
            )
            ->add(
                'name',
                TextType::class
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
            ]
        );
    }
}
