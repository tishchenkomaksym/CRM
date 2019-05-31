<?php


namespace App\Service\Vacancy\CandidateLinkRelationsToCandidate\FormValidators;


use App\Service\Vacancy\CandidateEditRelationToCandidateLinkToCandidateVacancy\NoDataException;
use App\Service\Vacancy\CandidateVacancyRelationsToCandidate\VacancyCandidateBuilder\ExistsCandidateBuilder;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Form;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CandidateLinkCheckExistenceValidator extends ConstraintValidator
{
    private $message = 'This candidate was already added';
    /**
     * @var ExistsCandidateBuilder
     */
    private $existsCandidateBuilder;
    /**
     * @var CandidateLinkExistenceLogic
     */
    private $candidateLinkExistenceLogic;


    public function __construct(ExistsCandidateBuilder $existsCandidateBuilder,
                                CandidateLinkExistenceLogic $candidateLinkExistenceLogic)
    {
        $this->existsCandidateBuilder = $existsCandidateBuilder;
        $this->candidateLinkExistenceLogic = $candidateLinkExistenceLogic;
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     * @return void
     * @throws NoDataException
     */
    public function validate($value, Constraint $constraint):void
    {
        if (!$constraint instanceof CandidateLinkCheckExistence) {
            throw new UnexpectedTypeException($constraint, CandidateLinkCheckExistence::class);
        }

        $form = $this->context->getObject();
        if ($form instanceof Form) {
            $name = $form->get('name')->getData();
            $surname = $form->get('surname')->getData();
            $candidate = $this->existsCandidateBuilder->build($name, $surname);
            if (($candidate !== null) && !empty($this->candidateLinkExistenceLogic->existence($candidate->getId(),
                    [$constraint->vacancyLink->getId()]))) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
            if (($candidate !== null) && !empty($this->candidateLinkExistenceLogic->existenceVacancy($candidate->getId(),
                    [$constraint->vacancyLink->getId()]))) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
        }
    }
}