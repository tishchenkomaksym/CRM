<?php


namespace App\Service\Vacancy\Letters\CreateForDepartmentManagerSetTime;


use App\Data\Sdt\Mail\Adapter\NoDateException;
use App\Service\Vacancy\CreateCandidateVacancyLinkForLetter\CandidateLinkVacancyInterface;
use Swift_Message;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CreateForDepartmentManagerSetTime
{
    private $templating;

    public function __construct(Environment $templating)
    {

        $this->templating = $templating;

    }

    /**
     * @param CandidateLinkVacancyInterface $candidateVacancyStrategy
     * @return Swift_Message
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function build(CandidateLinkVacancyInterface $candidateVacancyStrategy): Swift_Message
    {

        $email = $candidateVacancyStrategy->departmentManagerEmail();

        $letter = new Swift_Message( 'Interview date and time for candidate' . $candidateVacancyStrategy->candidateName() .
            $candidateVacancyStrategy->candidateSurname() . 'under Vacancy#' . $candidateVacancyStrategy->vacancyId() . 'have been set');
        $letter
            ->setFrom(getenv('LOCAL_EMAIL'))
            ->setTo($email)
            ->setBody(
                $this->templating->render(
                    'emails/vacancy/setTimeDepartmentManager.html.twig',
                    [
                        'candidateVacancy' => $candidateVacancyStrategy,
                    ]
                ),
                'text/html'
            );
        return $letter;
    }
}