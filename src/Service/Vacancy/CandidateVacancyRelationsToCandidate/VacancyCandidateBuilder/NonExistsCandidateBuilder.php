<?php


namespace App\Service\Vacancy\CandidateVacancyRelationsToCandidate\VacancyCandidateBuilder;


use App\Entity\Candidate;
use App\Entity\CandidateVacancy;
use App\Entity\CandidateVacancyHistory;
use App\Entity\Vacancy;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class NonExistsCandidateBuilder
{
    private $entityManager;

    private $user;
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    public function __construct(ObjectManager $entityManager,
        TokenStorageInterface $tokenStorage,
        ParameterBagInterface $parameterBag)
    {
        $this->entityManager = $entityManager;
        if ($tokenStorage->getToken() !== null ){
            $this->user = $tokenStorage->getToken()->getUser();
        }
        $this->parameterBag = $parameterBag;
    }

    public function build(string $name,
                          string $surname, Vacancy $vacancy, $from, $receivedCv): Candidate
    {
        $candidate = new Candidate();
        $candidateVacancy = new CandidateVacancy();

        $candidate
            ->setName($name)
            ->setSurname($surname)
            ->setPhone('')
            ->setPosition('')
            ->setLocation('')
            ->setEmail('')
            ->setCreatedAt(new DateTimeImmutable('now'));

        $candidateVacancy
            ->setVacancy($vacancy)
            ->setCandidate($candidate)
            ->setCandidateFrom($from)
            ->setCreatedAt(new DateTimeImmutable('now'))
            ->setCreatedBy($this->user)
            ->setReceivedCv($receivedCv);

        if ($receivedCv !== null) {
            /** @var UploadedFile $file */
            $file = $receivedCv;
            $fileName = md5(uniqid('', true)) . '.' . $file->guessExtension();
            $file->move($this->parameterBag->get('uploads_directory'), $fileName);
            $candidateVacancy->setReceivedCV($fileName);
        }

        $candidateVacancyHistory = new CandidateVacancyHistory();
        $candidateVacancyHistory
            ->setCandidateStatus('CV Received')
            ->setUpdatedAt(new DateTime())
            ->setCandidateVacancy($candidateVacancy);
        $this->entityManager->persist($candidateVacancyHistory);
        $this->entityManager->persist($candidateVacancy);
        $this->entityManager->persist($candidate);
        $this->entityManager->flush();
        return $candidate;
    }
}