<?php

namespace App\Form;

use App\Entity\Candidate;
use App\Entity\Vacancy;
use App\Repository\VacancyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CandidateType extends AbstractType
{

    private $vacancies;

    public function __construct(VacancyRepository $vacancyRepository)
    {
        $ar = [
            'Issue have been assigned',
            'CV Received',
            'Candidates Interest is checked',
            'CV approved by Department Manager',
            'Interview Timing approval',
            'Interview',
            'Contract Concluding',
            'Start date of new employee is set',
            'New employee onboarding'
        ];

        $st = [
            'status' => $ar
        ];
        $collection = new ArrayCollection();
        $tmpVacancies = $vacancyRepository->findBy($st);
        foreach ($tmpVacancies as $tmpVacancy) {
            $collection->add($tmpVacancy);
        }
        $this->vacancies = $collection;


    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('photo',FileType::class,[
                'required' => false
            ])
            ->add('name')
            ->add('position')
            ->add('location')
            ->add('vacancy', EntityType::class, [
                    'class' => Vacancy::class,
                    'choice_label' => 'id',
                    'multiple'     => true,
                    'choices' => $this->vacancies
                ]
            )
            ->add('phone')
            ->add('email')
            ->add('linkedIn')
            ->add('facebook')
            ->add('salary')
            ->add('experience')
            ->add('education')
            ->add('employment')
            ->add('comment');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Candidate::class,
        ]);
    }
}
