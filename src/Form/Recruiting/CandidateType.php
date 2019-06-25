<?php

namespace App\Form\Recruiting;

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
        $statuses = [
            'Issue have been assigned',
            'Search for a candidate(s) have been started',
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
            ->add('photo',FileType::class,[
                'required' => false
            ])

            ->add('name')
            ->add('surname')
            ->add('currentPosition')
            ->add('applyingPosition')
            ->add('location')
            ->add('vacancy', EntityType::class, [
                    'class' => Vacancy::class,
                    'choice_label' => 'id',
                    'multiple'     => true,
                    'choices' => $this->vacancies,
                    'mapped' => false,
                    'required' => false
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
