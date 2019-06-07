<?php


namespace App\Form\Recruiting;


use App\Constants\FormType;
use App\Entity\CandidateVacancy;
use App\Repository\CandidateRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CandidateStepCvReceivedTypeForRecommendation extends AbstractType
{

    private $name;

    public function  __construct(CandidateRepository $candidateRepository)
    {
        $candidateNames = $candidateRepository->findByExampleName();

        foreach ($candidateNames as $candidateName) {
            $name = $candidateName->getName();
            $this->name[$name] = $name;
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', ChoiceType::class, [
                    FormType::LABEL => 'Name',
                    'choices' => $this->name,
                    'mapped' => false,
                ]
            )
            ->add('surname', null, [
                    'attr' => ['placeholder' => 'Enter Surname'],
                    'mapped' => false
                ]
            )
            ->add('receivedCv',FileType::class,[
                    'data_class' => null,
                    'required' => false


                ]
            )
            ->add('linkToCv', null, [
                    'attr' => ['placeholder' => 'Enter Link to CV'],
                    'mapped' => false,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CandidateVacancy::class,
        ]);
    }
}