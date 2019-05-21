<?php


namespace App\Service\Vacancy\CandidateLinkRelationsToCandidate\VacancyCandidateBuilder;


use App\Entity\Candidate;
use App\Entity\CandidateLink;
use App\Entity\VacancyLink;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FormVacancyCandidateBuilderLinks
{

    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    public  function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function build(CandidateLink $candidateLink, Candidate $candidate, VacancyLink $vacancyLink,string $from): CandidateLink
    {
        if ($candidateLink->getReceivedCV() !== null) {
            /** @var UploadedFile $file */
            $file = $candidateLink->getReceivedCv();
            $fileName = md5(uniqid('', true)) . '.' . $file->guessExtension();
            $file->move($this->parameterBag->get('uploads_directory'), $fileName);
            $candidateLink->setReceivedCV($fileName);
        }

        $candidateLink
            ->setCandidate($candidate)
            ->setVacancyLink($vacancyLink)
            ->setCandidateFrom($from);
        return $candidateLink;
    }
}