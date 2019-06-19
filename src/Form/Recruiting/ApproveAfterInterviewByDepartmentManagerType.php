<?php


namespace App\Form\Recruiting;


use App\Entity\CandidateManagerApproval;
use App\Entity\Department;
use App\Entity\Office;
use App\Entity\Team;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApproveAfterInterviewByDepartmentManagerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('office', EntityType::class, [
                    'attr' => ['class' => 'office'],
                    'placeholder' => 'Select Office',
                    'class' => Office::class,
                    'choice_label' => 'name',
                    'choice_name' => 'name',
                    'required' => false,
                    'mapped' => false,
                    'label' => false,
                    'choice_attr' => static function (Office $choiceValue) {
                        return ['data-Office' => $choiceValue->getId(),];
                    },
                ]
            )
            ->add('department', EntityType::class, [
                    'attr' => ['class' => 'department'],
                    'placeholder' => 'Select Department',
                    'class' => Department::class,
                    'choice_label' => 'name',
                    'required' => false,
                    'mapped' => false,
                    'label' => false,
                    'choice_attr' => static function (Department $choiceValue) {
                        return ['data-OfficeId' => $choiceValue->getOffice()->getId()];
                    },
                ]
            )
            ->add('team', EntityType::class, [
                    'attr' => ['class' => 'team'],
                    'placeholder' => 'Select Team',
                    'class' => Team::class,
                    'choice_label' => 'name',
                    'label' => false,
                    'choice_attr' => static function (Team $choiceValue) {
                        return ['data-DepartmentId' => $choiceValue->getDepartment()->getId()];
                    },
                ]
            )
            ->add('directionEnterpreneur', null, [
                'attr' => ['placeholder' => 'Укажите направление услуг ЧП'],
                'label' => false
            ])
            ->add('level', null, [
                'attr' => ['placeholder' => 'Уровень подготовки/Level (junior middle senior)'],
                'label' => false
            ])
            ->add('startDate', DateType::class,[
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'attr' => ['placeholder' => 'Рекомендуемая дата выхода'],
                'label' => false
            ])
            ->add('salary', null, [
                'attr' => ['placeholder' => 'Оговоренная оплата оказываемых услуг по контракту ($)'],
                'label' => false
            ])
            ->add('workPlace', null, [
                'attr' => ['placeholder' => 'Рекомендуемое расположение рабочего места (кабинет)(Необходимо указать №кабинета, при наличии стола - указать номер стола, при отсутствии стола - слева/справа от PC№ЧЧЧЧ/'],
                'label' => false

            ])
            ->add('nickname', null, [
                'attr' => ['placeholder' => 'Псевдоним (заполняется только для офиса Спасской, позиция caller)'],
                'label' => false
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CandidateManagerApproval::class,
        ]);
    }
}