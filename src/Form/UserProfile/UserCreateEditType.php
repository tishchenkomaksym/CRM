<?php


namespace App\Form\UserProfile;


use App\Entity\UserInfo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserCreateEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'photo',FileType::class,[
                    'required' => false
                ]
            )
            ->add(
                $builder->create('user', UserEditType::class, [
                    'by_reference' => true,
                    'label' => false
                ]))
            ->add('position')
            ->add('subTeam')
            ->add('phone')
            ->add('skype')
            ->add('personalEmail')
            ->add('salary')
            ->add('sex', ChoiceType::class, [
                    'placeholder' => 'Choose sex',
                    'choices' => [
                        'male' => 'male',
                        'female' => 'female',
                    ],
                ]
            )
            ->add('birthDay', BirthdayType::class, [
                    'placeholder' => 'Select a value',
                ]
            )
            ->add('maritalStatus', ChoiceType::class, [
                    'placeholder' => 'Choose marital status',
                    'choices' => [
                        'single' => 'single',
                        'married' => 'married',
                        'divorced' => 'divorced'
                    ],
                ]
            )
            ->add('children')
            ->add('location');

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => UserInfo::class,
            ]
        );
    }
}