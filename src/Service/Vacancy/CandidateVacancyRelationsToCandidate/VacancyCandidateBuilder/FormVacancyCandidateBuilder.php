<?php


namespace App\Service\Vacancy\CandidateVacancyRelationsToCandidate\VacancyCandidateBuilder;


use App\Entity\Candidate;
use App\Entity\CandidateLink;
use App\Entity\CandidateVacancy;
use App\Entity\Vacancy;
use App\Entity\VacancyLink;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FormVacancyCandidateBuilder
{

    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    public  function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function build(CandidateVacancy $candidateVacancy, Candidate $candidate, Vacancy $vacancy,string $from): CandidateVacancy
    {
        if ($candidateVacancy->getReceivedCV() !== null) {
            /** @var UploadedFile $file */
            $file = $candidateVacancy->getReceivedCv();
            $fileName = md5(uniqid('', true)) . '.' . $file->guessExtension();
            $file->move($this->parameterBag->get('uploads_directory'), $fileName);
            $candidateVacancy->setReceivedCV($fileName);
        }
        if ($candidateVacancy->getLinkToProfile1() === null || $candidateVacancy->getLinkToProfile2() === null
            || $candidateVacancy->getLinkToProfile3() === null || $candidateVacancy->getLinkToProfile4() === null){
            $candidateVacancy
                ->setCandidate($candidate)
                ->setVacancy($vacancy)
                ->setCandidateFrom($from);
        }else{
            $candidateVacancy
                ->setCandidate($candidate)
                ->setVacancy($vacancy)
                ->setCandidateFrom($from)
                ->setLinkToProfile1($candidateVacancy->getLinkToProfile1())
                ->setLinkToProfile2($candidateVacancy->getLinkToProfile2())
                ->setLinkToProfile3($candidateVacancy->getLinkToProfile3())
                ->setLinkToProfile4($candidateVacancy->getLinkToProfile4());
        }
        return $candidateVacancy;
    }

}