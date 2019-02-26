<?php

namespace App\Form;

use App\Entity\MonthlySdt;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MonthlySdtType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('count', \Symfony\Component\Form\Extension\Core\Type\IntegerType::class)
            ->add('create_date')
            ->add(
                'user_id',
                EntityType::class,
                [
                    'class' => User::class,
                    'choice_label' => 'name'
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => MonthlySdt::class,
            ]
        );
    }
}
