<?php


namespace App\Form\Recruiting;


use App\Entity\EmployeeOnBoardingInfo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CandidateOnBoardingInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullName',null,[
                'attr' => ['placeholder' => 'Укажите имя нового сотрудника транслитом с украинского']
            ])
            ->add('sex',ChoiceType::class, [
                    'placeholder' => 'Укажите пол нового сотрудника',
                    'choices' => [
                        'Male' => 'Male',
                        'Female' => 'Female',
                    ],
                ]
            )
            ->add('birthDay', BirthdayType::class, [
                'placeholder' => 'Select a value',
                'required' => false
            ]
        )
            ->add('maritalStatus',ChoiceType::class, [
                'placeholder' => 'Укажите семейное положение нового сотрудника',
                'choices' => [
                    'Single' => 'Single',
                    'Married' => 'Married',
                    'Divorced' => 'Divorced',
                ],
            ]
        )
            ->add('children', null, [
                'attr' => ['placeholder' => 'Укажите есть ли у нового сотрудника дети (если есть - укажите сколько']
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EmployeeOnBoardingInfo::class,
        ]);
    }
}