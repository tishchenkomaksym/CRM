<?php


namespace App\Service\Vacancy\CandidateLinkRelationsToCandidate\VacancyCandidateBuilder;


use App\Entity\Candidate;
use App\Entity\CandidateLink;
use App\Entity\CandidateVacancyHistory;
use App\Entity\User;
use App\Entity\VacancyLink;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class NonExistsCandidateBuilderLinks
{
    private $entityManager;

    /**
     * @var User
     */
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
        if (($tokenStorage->getToken() !== null) && $tokenStorage->getToken()->getUser() instanceof User) {
            $this->user = $tokenStorage->getToken()->getUser();
        }
        $this->parameterBag = $parameterBag;
    }

    public function build(string $name,
                          string $surname, VacancyLink $vacancyLink, $from, $receivedCv): Candidate
    {
        $candidate = new Candidate();
        $candidateLink = new Candidatelink();
        $candidate
            ->setName($name)
            ->setSurname($surname)
            ->setPhone('')
            ->setLocation('')
            ->setEmail('')
            ->setCreatedBy($this->user)
            ->setCreatedAt(new DateTimeImmutable('now'));

        $candidateLink
            ->setVacancyLink($vacancyLink)
            ->setCandidate($candidate)
            ->setCandidateFrom($from)
            ->setCreatedAt(new DateTimeImmutable('now'))
            ->setCreatedBy($this->user);

        if ($receivedCv !== null) {
            /** @var UploadedFile $file */
            $file = $receivedCv;
            $fileName = md5(uniqid('', true)) . '.' . $file->guessExtension();
            $file->move($this->parameterBag->get('uploads_directory'), $fileName);
            $candidateLink->setReceivedCV($fileName);
        }

        $candidateVacancyHistory = new CandidateVacancyHistory();
        $candidateVacancyHistory
            ->setCandidateStatus('CV Received')
            ->setUpdatedAt(new DateTime())
            ->setCandidateLink($candidateLink);
        $this->entityManager->persist($candidateVacancyHistory);
        $this->entityManager->persist($candidate);
        $this->entityManager->persist($candidateLink);
        $this->entityManager->flush();
        return $candidate;
    }
}