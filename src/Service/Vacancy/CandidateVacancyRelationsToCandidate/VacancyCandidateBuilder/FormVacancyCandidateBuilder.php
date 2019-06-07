<?php


namespace App\Service\Vacancy\CandidateVacancyRelationsToCandidate\VacancyCandidateBuilder;


use App\Entity\Candidate;
use App\Entity\CandidateVacancy;
use App\Entity\Vacancy;
use DateTimeImmutable;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class FormVacancyCandidateBuilder
{

    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    private $user;

    public  function __construct(ParameterBagInterface $parameterBag, TokenStorageInterface $tokenStorage)
    {
        $this->parameterBag = $parameterBag;
        if ($tokenStorage->getToken() !== null ){
            $this->user = $tokenStorage->getToken()->getUser();
        }
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
                ->setCandidateFrom($from)
                ->setCreatedAt(new DateTimeImmutable('now'))
                ->setCreatedBy($this->user);

        }else{
            $candidateVacancy
                ->setCandidate($candidate)
                ->setVacancy($vacancy)
                ->setCandidateFrom($from)
                ->setLinkToProfile1($candidateVacancy->getLinkToProfile1())
                ->setLinkToProfile2($candidateVacancy->getLinkToProfile2())
                ->setLinkToProfile3($candidateVacancy->getLinkToProfile3())
                ->setLinkToProfile4($candidateVacancy->getLinkToProfile4())
                ->setCreatedAt(new DateTimeImmutable('now'))
                ->setCreatedBy($this->user);
        }
        return $candidateVacancy;
    }

}