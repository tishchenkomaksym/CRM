<?php


namespace App\Form\UserProfile;


use App\Entity\Department;
use App\Entity\Office;
use App\Entity\Team;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class
            )

            ->add('office', EntityType::class, [
                    'attr' => ['class' => 'office'],
                    'placeholder' => 'Select Office',
                    'class' => Office::class,
                    'choice_label' => 'name',
                    'choice_name' => 'name',
                    'required' => false,
                    'mapped' => false,
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
                    'choice_attr' => static function (Department $choiceValue) {
                        return ['data-OfficeId' => $choiceValue->getOffice()->getId()];
                    },
                ]
            )
            ->add('team', EntityType::class, [
//                    'attr' => ['class' => 'team'],
                    'placeholder' => 'Select Team',
                    'class' => Team::class,
                    'choice_label' => 'name',
                    'choice_attr' => static function (Team $choiceValue) {
                        return ['data-DepartmentId' => $choiceValue->getDepartment()->getId()];
                    },
                ]
            )
            ->add(
                'email',
                TextType::class
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