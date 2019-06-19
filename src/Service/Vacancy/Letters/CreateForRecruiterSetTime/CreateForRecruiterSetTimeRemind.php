<?php


namespace App\Service\Vacancy\Letters\CreateForRecruiterSetTime;

use App\Service\Vacancy\CreateCandidateVacancyLinkForLetter\CandidateLinkVacancyInterface;
use Swift_Message;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CreateForRecruiterSetTimeRemind
{

    private $templating;

    public function __construct(Environment $templating)
    {

        $this->templating = $templating;

    }

    /**
     * @param CandidateLinkVacancyInterface $candidateVacancyProvider
     * @return Swift_Message
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function build(CandidateLinkVacancyInterface $candidateVacancyProvider): Swift_Message
    {

        $email = $candidateVacancyProvider->recruiterEmail();
        $letter = new Swift_Message( 'Reminder of Interview date and time for candidate' . $candidateVacancyProvider->candidate()->getName() .
            $candidateVacancyProvider->candidate()->getSurname() . 'under Vacancy#' . $candidateVacancyProvider->vacancy()->getId());
        $letter
            ->setFrom(getenv('LOCAL_EMAIL'))
            ->setTo($email)
            ->setBody(
                $this->templating->render(
                    'emails/vacancy/setTimeRecruiter.html.twig',
                    [
                        'candidateVacancy' => $candidateVacancyProvider,

                    ]
                ),
                'text/html'
            );
        return $letter;
    }
}