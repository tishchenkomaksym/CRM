<?php


namespace App\Form\Recruiting;

use App\Entity\CandidateOfferDeny;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class DenyAfterInterviewNotSuitableSalaryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('desiredSalary', null,[
                'label' => 'Вы указали что кандидат отказался от нашего оффера из-за предложенной
                 суммы. Если у Вас имеется такая информация, укажите на какую сумму кандидат готов
                  бы был согласиться:',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CandidateOfferDeny::class,
        ]);
    }
}