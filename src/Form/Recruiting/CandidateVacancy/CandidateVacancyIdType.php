<?php


namespace App\Form\Recruiting\CandidateVacancy;


use App\Entity\Candidate;
use App\Entity\Vacancy;
use App\Repository\VacancyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CandidateVacancyIdType extends AbstractType
{
    private $vacancies;

    public function __construct(VacancyRepository $vacancyRepository)
    {
        $statuses = [
            'Issue have been assigned',
            'Search for a candidate(s) have been started',
            'CV Received',
            'Candidates Interest is checked',
            'CV approved by Department Manager',
            'Interview Timing approval',
            'Interview',
            'Contract Concluding',
            'Start date of new employee is set',
            'New employee onboarding'
        ];

        $status = [
            'status' => $statuses
        ];
        $collection = new ArrayCollection();
        $tmpVacancies = $vacancyRepository->findBy($status);
        foreach ($tmpVacancies as $tmpVacancy) {
            $collection->add($tmpVacancy);
        }
        $this->vacancies = $collection;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('vacancy', EntityType::class, [
                    'class' => Vacancy::class,
                    'choice_label' => 'id',
                    'multiple'     => true,
                    'choices' => $this->vacancies,
                    'mapped' => false,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Candidate::class,
        ]);
    }
}