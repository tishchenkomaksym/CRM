<?php


namespace App\Service\Vacancy\Letters\CreateEmployeeStartDateForRecruiter;


use App\Service\Vacancy\CandidateEditRelationToCandidateLinkToCandidateVacancy\NoDataException;
use App\Service\Vacancy\CreateCandidateVacancyLinkForLetter\CandidateLinkVacancyInterface;
use Swift_Message;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CreateEmployeeStartDateForRecruiter
{
    private $templating;

    public function __construct(Environment $templating)
    {

        $this->templating = $templating;

    }

    /**
     * @param CandidateLinkVacancyInterface $candidateLinkVacancy
     * @return Swift_Message
     * @throws LoaderError
     * @throws NoDataException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function build(CandidateLinkVacancyInterface $candidateLinkVacancy): Swift_Message
    {
        if ($candidateLinkVacancy->vacancy()->getAssignee() === null){
            throw new NoDataException('Assignee not found');
        }
        $email = $candidateLinkVacancy->vacancy()->getAssignee()->getEmail();
        $letter = new Swift_Message( 'Start date of new employee ||' . $candidateLinkVacancy->candidate()->getName() .
            $candidateLinkVacancy->candidate()->getSurname());
        $letter
            ->setFrom(getenv('LOCAL_EMAIL'))
            ->setTo($email)
            ->setBody(
                $this->templating->render(
                    'emails/vacancy/startDateEmployeeForRecruiter.html.twig',
                    [
                        'candidateVacancy' => $candidateLinkVacancy,

                    ]
                ),
                'text/html'
            );
        return $letter;
    }
}