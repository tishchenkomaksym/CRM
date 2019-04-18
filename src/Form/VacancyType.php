<?php

namespace App\Form;

use App\Entity\Department;
use App\Entity\Office;
use App\Entity\Team;
use App\Entity\Vacancy;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VacancyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('office', EntityType::class, [
                    'placeholder' => 'Select Office',
                    'class' => Office::class,
                    'choice_label' => 'name',
                    'choice_name' => 'name',
                    'choice_attr' => static function (Office $choiceValue) {
                        return ['data-Office' => $choiceValue->getId()];
                    },
                ]
            )
            ->add('department', EntityType::class, [
                    'placeholder' => 'Select Department',
                    'class' => Department::class,
                    'choice_label' => 'name',
                    'choice_attr' => static function (Department $choiceValue) {
                        return ['data-OfficeId' => $choiceValue->getOffice()->getId()];
                    },
                ]
            )
            ->add('team', EntityType::class, [
                    'placeholder' => 'Select Team',
                    'class' => Team::class,
                    'choice_label' => 'name',
                    'choice_attr' => static function (Team $choiceValue) {
                        return ['data-DepartmentId' => $choiceValue->getDepartment()->getId()];
                    },
                ]
            )
            ->add('position', null, [
                    'attr' => ['placeholder' => 'Enter position']
                ]
            )
            ->add('salary', null, [
                    'attr' => ['placeholder' => 'Enter expected salary, USD']
                ]
            )
            ->add('test', ChoiceType::class, [
                    'placeholder' => 'Is there a test task?',
                    'choices' => [
                        'Yes' => 'yes',
                        'No' => 'no',
                    ],
                ]
            )
            ->add('english', ChoiceType::class, [
                    'placeholder' => 'English',
                    'choices' => [
                        'Elementary' => 'Elementary',
                        'Pre-intermediate' => 'Pre-intermediate',
                        'Intermediate' => 'Intermediate',
                        'Upper-Intermediate' => 'Upper-Intermediate',
                        'Advanced' => 'Advanced',
                    ],
                ]
            )
            ->add('amount', null, [
                    'attr' => ['placeholder' => 'Enter required amount of employees']
                ]

            )
            ->add('bonus', null, [
                    'attr' => ['placeholder' => 'Bonus system if there is one']
                ]
            )
            ->add('responsibilities', null, [
                    'attr' => ['placeholder' => 'Enter responsibilities']
                ]
            )
            ->add('requirements', null, [
                    'attr' => ['placeholder' => 'Enter requirements']
                ]
            )
            ->add('plus', null, [
                    'attr' => ['placeholder' => 'Would be a plus']
                ]
            )
            ->add('reason', null, [
                    'attr' => ['placeholder' => 'Enter request reason']
                ]
            );
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vacancy::class,
        ]);
    }
}
