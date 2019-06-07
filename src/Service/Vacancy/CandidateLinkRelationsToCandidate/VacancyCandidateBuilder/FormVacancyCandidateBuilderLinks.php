<?php


namespace App\Service\Vacancy\CandidateLinkRelationsToCandidate\VacancyCandidateBuilder;


use App\Entity\Candidate;
use App\Entity\CandidateLink;
use App\Entity\VacancyLink;
use DateTimeImmutable;
use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class FormVacancyCandidateBuilderLinks
{

    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    /**
     * @var TokenStorageInterface
     */
    private $user;

    public  function __construct(ParameterBagInterface $parameterBag, TokenStorageInterface $tokenStorage)
    {
        $this->parameterBag = $parameterBag;
        if ($tokenStorage->getToken() !== null ){
            $this->user = $tokenStorage->getToken()->getUser();
        }
    }

    /**
     * @param CandidateLink $candidateLink
     * @param Candidate $candidate
     * @param VacancyLink $vacancyLink
     * @param string $from
     * @return CandidateLink
     * @throws Exception
     */
    public function build(CandidateLink $candidateLink, Candidate $candidate, VacancyLink $vacancyLink, string $from): CandidateLink
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
            ->setCandidateFrom($from)
            ->setCreatedAt(new DateTimeImmutable('now'))
            ->setCreatedBy($this->user);
        return $candidateLink;
    }
}