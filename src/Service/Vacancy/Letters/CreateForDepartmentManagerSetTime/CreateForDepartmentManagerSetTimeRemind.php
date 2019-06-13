<?php


namespace App\Service\Vacancy\Letters\CreateForDepartmentManagerSetTime;

use App\Service\Vacancy\CreateCandidateVacancyLinkForLetter\CandidateLinkVacancyInterface;
use Swift_Message;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CreateForDepartmentManagerSetTimeRemind
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

        $email = $candidateVacancyProvider->departmentManagerEmail();

        $letter = new Swift_Message( 'Reminder of Interview date and time for candidate' . $candidateVacancyProvider->candidateName() .
            $candidateVacancyProvider->candidateSurname() . 'under Vacancy#' . $candidateVacancyProvider->vacancyId());
        $letter
            ->setFrom(getenv('LOCAL_EMAIL'))
            ->setTo($email)
            ->setBody(
                $this->templating->render(
                    'emails/vacancy/setTimeDepartmentManager.html.twig',
                    [
                        'candidateVacancy' => $candidateVacancyProvider,

                    ]
                ),
                'text/html'
            );
        return $letter;
    }
}