<?php


namespace App\Form\Recruiting;


use App\Entity\CandidateManagerDeny;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecruiterReportedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('recruiterReported', CheckboxType::class,[
                'empty_data' => true,
                'label' => 'Если уже дали фидбек кандидату - поставьте галочку. 
                У Вас уже не будет возможности вернуться на этот шаг.',
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CandidateManagerDeny::class,
        ]);
    }
}