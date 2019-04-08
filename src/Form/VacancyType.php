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
                    'choice_attr' => static function(Office $choiceValue, $key, $value) {
                        return ['data-Office' => $choiceValue->getId() ];
                    },
                ]
            )
            ->add('department', EntityType::class, [
                    'placeholder' => 'Select Department',
                    'class' => Department::class,
                    'choice_label' => 'name',
                    'choice_attr' => static function(Department $choiceValue, $key, $value) {
                        return ['data-OfficeId' => $choiceValue->getOfficeId() ];
                    },
                ]
            )
            ->add('team', EntityType::class, [
                    'placeholder' => 'Select Team',
                    'class' => Team::class,
                    'choice_label' => 'name',
                    'choice_attr' => static function(Team $choiceValue, $key, $value) {
                        return ['data-DepartmentId' => $choiceValue->getDepartmentId() ];
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
                    'choices'  => [
                        'Yes' => true,
                        'No' => false,
                    ],
                ]
            )
            ->add('english', ChoiceType::class, [
                    'placeholder' => 'English',
                    'choices'  => [
                        'Elementary' => true,
                        'Pre-intermediate' => true,
                        'Intermediate' => true,
                        'Upper-Intermediate' => true,
                        'Advanced' => true,
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
            ->add('reason',  null, [
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
