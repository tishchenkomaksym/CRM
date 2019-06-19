<?php


namespace App\Form\Recruiting;

use App\Entity\CandidateOfferDeny;
use App\Entity\DenyChoiceCandidate;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DenyAfterInterviewByCandidateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('choices',EntityType::class,[
                'class' => DenyChoiceCandidate::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required'=>true,
                'expanded' => true,
                'label' => false,
                'mapped' => false

            ])
            ->add('noSuitableReason', null,[
                'label' => 'Если в списке выше нет подходящей причины, или Вы хотите какой-то из пунков расписать 
                подробнее, сделайте это в поле ниже" (необязательное для заполнения поле)',
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CandidateOfferDeny::class,
        ]);
    }
}