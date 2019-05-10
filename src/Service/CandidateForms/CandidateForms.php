<?php


namespace App\Service\CandidateForms;


use App\Entity\Candidate;
use App\Entity\Vacancy;
use App\Entity\VacancyLink;
use App\Form\CandidateStepCvReceivedType;
use App\Form\CandidateVacancyFromVacancy;
use App\Repository\CandidateRepository;
use App\Repository\VacancyLinkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class CandidateForms
{
    /**
     * @var VacancyLinkRepository
     */
    private $vacancyLinkRepository;

    public function __construct(VacancyLinkRepository $vacancyLinkRepository)
    {
        $this->vacancyLinkRepository = $vacancyLinkRepository;
    }

    public function vacancyLink(Vacancy $vacancy):array
    {
        return $this->vacancyLinkRepository->findBy([
           'vacancy' => $vacancy->getId()
        ]);
    }
}