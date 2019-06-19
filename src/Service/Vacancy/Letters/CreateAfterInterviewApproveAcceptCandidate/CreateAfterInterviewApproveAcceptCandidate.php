<?php


namespace App\Service\Vacancy\Letters\CreateAfterInterviewApproveAcceptCandidate;


use App\Entity\CandidateManagerApproval;
use App\Service\Vacancy\CandidateEditRelationToCandidateLinkToCandidateVacancy\NoDataException;
use Swift_Message;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CreateAfterInterviewApproveAcceptCandidate
{

    private $templating;

    public function __construct(Environment $templating)
    {

        $this->templating = $templating;

    }

    /**
     * @param CandidateManagerApproval $candidateManagerApproval
     * @return Swift_Message
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws NoDataException
     */
    public function build(CandidateManagerApproval $candidateManagerApproval): Swift_Message
    {
        $email = ['yuliia.ly@nexteum.com', 'valeriia.ho@nexteum.com'];
        if ($candidateManagerApproval->getCandidateVacancy() !== null){
            if ($candidateManagerApproval->getCandidateVacancy()->getCandidate() === null ||
                $candidateManagerApproval->getCandidateVacancy()->getVacancy() === null ||
                $candidateManagerApproval->getCandidateVacancy()->getVacancy()->getAssignee() === null ||
                $candidateManagerApproval->getCandidateVacancy()->getVacancy()->getAssignedBy() === null ||
                $candidateManagerApproval->getCandidateVacancy()->getVacancy()->getCreatedBy() === null){
                throw new NoDataException('Wrong configuration of CandidateVacancy');
            }
            $name = $candidateManagerApproval->getCandidateVacancy()->getCandidate()->getName();
            $surname = $candidateManagerApproval->getCandidateVacancy()->getCandidate()->getSurname();
            $email[] = $candidateManagerApproval->getCandidateVacancy()->getVacancy()->getAssignee()->getEmail();
            $email[] = $candidateManagerApproval->getCandidateVacancy()->getVacancy()->getAssignedBy()->getEmail();
            $email[] = $candidateManagerApproval->getCandidateVacancy()->getVacancy()->getCreatedBy()->getEmail();
        }else{
            if ($candidateManagerApproval->getCandidateLink() === null ||
                $candidateManagerApproval->getCandidateLink()->getCandidate() === null ||
                $candidateManagerApproval->getCandidateLink()->getVacancyLink() === null ||
                $candidateManagerApproval->getCandidateLink()->getVacancyLink()->getVacancy() === null ||
                $candidateManagerApproval->getCandidateLink()->getVacancyLink()->getVacancy()->getAssignee() === null ||
                $candidateManagerApproval->getCandidateLink()->getVacancyLink()->getVacancy()->getAssignedBy() === null ||
                $candidateManagerApproval->getCandidateLink()->getVacancyLink()->getVacancy()->getCreatedBy() === null){
                throw new NoDataException('Wrong configuration of CandidateLink');
            }
            $name = $candidateManagerApproval->getCandidateLink()->getCandidate()->getName();
            $surname = $candidateManagerApproval->getCandidateLink()->getCandidate()->getSurname();
            $email[] = $candidateManagerApproval->getCandidateLink()->getVacancyLink()->getVacancy()->getAssignee()->getEmail();
            $email[] = $candidateManagerApproval->getCandidateLink()->getVacancyLink()->getVacancy()->getAssignedBy()->getEmail();
            $email[] = $candidateManagerApproval->getCandidateLink()->getVacancyLink()->getVacancy()->getCreatedBy()->getEmail();
        }

        $letter = new Swift_Message( 'Contract Сonclusion - finall results ||' . $surname . $name);
        $letter
            ->setFrom(getenv('LOCAL_EMAIL'))
            ->setTo($email)
            ->setBody(
                $this->templating->render(
                    'emails/vacancy/afterInterviewApprove.html.twig',
                    [
                        'candidateVacancy' => $candidateManagerApproval,

                    ]
                ),
                'text/html'
            );
        return $letter;
    }
}