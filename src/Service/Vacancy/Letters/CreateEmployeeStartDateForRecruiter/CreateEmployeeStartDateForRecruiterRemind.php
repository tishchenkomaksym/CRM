<?php


namespace App\Service\Vacancy\Letters\CreateEmployeeStartDateForRecruiter;


use App\Service\CandidateForms\CandidateForms;
use App\Service\Vacancy\CreateCandidateVacancyLinkForLetter\CandidateLinkVacancyInterface;
use Swift_Message;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CreateEmployeeStartDateForRecruiterRemind
{
    private $templating;

    public function __construct(Environment $templating)
    {

        $this->templating = $templating;

    }

    /**
     * @param CandidateLinkVacancyInterface $candidateLinkVacancy
     * @param CandidateForms $candidateForms
     * @return Swift_Message
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function build(CandidateLinkVacancyInterface $candidateLinkVacancy, CandidateForms $candidateForms): Swift_Message
    {
        $vacancyLink = $candidateForms->vacancyLink($candidateLinkVacancy->vacancy());
        $candidateVacancy = $candidateForms->candidateVacancySearch($candidateLinkVacancy->vacancy(), $candidateLinkVacancy->candidate()->getId());
        $candidateLink = $candidateForms->candidateLinkSearch($vacancyLink, $candidateLinkVacancy->candidate()->getId());
        $email = $candidateLinkVacancy->recruiterEmail();
        $letter = new Swift_Message( 'Reminder to check start date of new employee ||' . $candidateLinkVacancy->candidate()->getName() .
            $candidateLinkVacancy->candidate()->getSurname());
        $letter
            ->setFrom(getenv('LOCAL_EMAIL'))
            ->setTo($email)
            ->setBody(
                $this->templating->render(
                    'emails/vacancy/startDateEmployeeForRecruiterRemind.html.twig',
                    [
                        'candidateVacancyLink' =>  $candidateLinkVacancy,
                        'candidateVacancy' => $candidateVacancy,
                        'candidateLink' => $candidateLink
                    ]
                ),
                'text/html'
            );
        return $letter;
    }
}