<?php


namespace App\Service\Vacancy\Letters\CreateEmployeeStartDateForManager;


use App\Service\Vacancy\CreateCandidateVacancyLinkForLetter\CandidateLinkVacancyInterface;
use Swift_Message;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CreateEmployeeStartDateForManagerEdit
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
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function build(CandidateLinkVacancyInterface $candidateLinkVacancy): Swift_Message
    {

        $email = $candidateLinkVacancy->departmentManagerEmail();
        $letter = new Swift_Message( 'Start date of new employee is changed ||' . $candidateLinkVacancy->candidate()->getName() .
            $candidateLinkVacancy->candidate()->getSurname());
        $letter
            ->setFrom(getenv('LOCAL_EMAIL'))
            ->setTo($email)
            ->setBody(
                $this->templating->render(
                    'emails/vacancy/startDateEmployeeEdit.html.twig',
                    [
                        'candidateVacancy' =>  $candidateLinkVacancy,

                    ]
                ),
                'text/html'
            );
        return $letter;
    }
}