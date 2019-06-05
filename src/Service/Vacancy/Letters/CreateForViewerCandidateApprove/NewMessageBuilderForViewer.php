<?php


namespace App\Service\Vacancy\Letters\CreateForViewerCandidateApprove;


use App\Data\Sdt\Mail\Adapter\NoDateException;
use App\Entity\Vacancy;
use App\Repository\CandidateRepository;
use Swift_Message;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class NewMessageBuilderForViewer
{

    private $templating;

    private $vacancy;

    /**
     * @var CandidateRepository
     */
    private $candidate;

    public function __construct(Vacancy $vacancy, $candidateId, CandidateRepository $candidateRepository, Environment $templating)
    {
        $this->vacancy = $vacancy;

        $this->templating = $templating;

        $this->candidate = $candidateRepository->findOneBy(['id' => $candidateId]);
    }

    /**
     * @return Swift_Message
     * @throws LoaderError
     * @throws NoDateException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function build(): Swift_Message
    {
        $viewer = $this->vacancy->getVacancyViewerUser();
        if (
            $viewer === null ||
            $viewer->getUser() === null ||
            $viewer->getUser()->getEmail() === null
        ) {
            throw new NoDateException('For vacancy№' . $this->vacancy->getId() .'appeared Candidate: ' . $this->candidate->getName());

        }
        $email = $viewer->getUser()->getEmail();
        $object = new Swift_Message('Candidates for vacancy you view have appeared');
        $object
            ->setFrom(getenv('LOCAL_EMAIL'))
            ->setTo($email)
            ->setBody(
                $this->templating->render(
                    'emails/vacancy/newCandidatesViewer.html.twig',
                    [
                        'vacancy' => $this->vacancy,
                        'candidate' => $this->candidate
                    ]
                ),
                'text/html'
            );
        return $object;
    }
}