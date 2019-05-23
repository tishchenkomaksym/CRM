<?php


namespace App\Form\Recruiting\CandidateLink;


use App\Entity\CandidateLink;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CandidateLinkCommentInterestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('commentInterest',null,[
                'required' => true
            ])
            ->add('save', SubmitType::class, ['label' => 'Candidate is Interested'])
            ->add('saveAndAdd', SubmitType::class, ['label' => 'Candidate is not Interested']);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CandidateLink::class,
        ]);
    }
}