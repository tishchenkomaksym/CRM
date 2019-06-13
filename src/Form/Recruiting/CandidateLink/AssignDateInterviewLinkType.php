<?php


namespace App\Form\Recruiting\CandidateLink;


use App\Constants\FormType;
use App\Entity\CandidateLink;
use App\Entity\ConfRoom;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssignDateInterviewLinkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateInterview', DateTimeType::class,
                [
                    'date_widget' => 'single_text',
                    FormType::LABEL => 'Assign Date and Time',
                ]
            )
            ->add('confRoom', EntityType::class, [
                'class' => ConfRoom::class,
                'choice_label' => 'name',
                'placeholder' => 'Выберите помещение'
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}