<?php


namespace App\Form\Recruiting;


use App\Entity\CandidateManagerDeny;
use App\Entity\DenyChoiceDepartment;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotBlank;

class DenyAfterInterviewByDepartmentManagerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('choices',EntityType::class,[
                'class' => DenyChoiceDepartment::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required'=>true,
                'expanded' => true,
                'label' => false,
                'mapped' => false,
                'constraints' => [new Count(['min' => 1])]

            ])
            ->add('impression', null,[
                'label' => false,
                ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CandidateManagerDeny::class,
        ]);
    }
}