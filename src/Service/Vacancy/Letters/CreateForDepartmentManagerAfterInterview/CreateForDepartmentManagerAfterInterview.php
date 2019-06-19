<?php


namespace App\Service\Vacancy\Letters\CreateForDepartmentManagerAfterInterview;


use App\Service\Vacancy\CreateCandidateVacancyLinkForLetter\CandidateLinkVacancyInterface;
use Swift_Message;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CreateForDepartmentManagerAfterInterview
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
        $letter = new Swift_Message( 'Result of interview with candidate' . $candidateVacancyProvider->candidateName() .
            $candidateVacancyProvider->candidateSurname());
        $letter
            ->setFrom(getenv('LOCAL_EMAIL'))
            ->setTo($email)
            ->setBody(
                $this->templating->render(
                    'emails/vacancy/afterInterviewDepartmentManager.html.twig',
                    [
                        'candidateVacancy' => $candidateVacancyProvider,

                    ]
                ),
                'text/html'
            );
        return $letter;
    }
}