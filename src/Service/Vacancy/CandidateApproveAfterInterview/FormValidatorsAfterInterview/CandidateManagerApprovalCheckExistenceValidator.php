<?php


namespace App\Service\Vacancy\CandidateApproveAfterInterview\FormValidatorsAfterInterview;

use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CandidateManagerApprovalCheckExistenceValidator extends ConstraintValidator
{
    /**
     * @var CandidateManagerApprovalExistenceLogic
     */
    private $managerApprovalExistenceLogic;


    public function __construct(CandidateManagerApprovalExistenceLogic $managerApprovalExistenceLogic)
    {

        $this->managerApprovalExistenceLogic = $managerApprovalExistenceLogic;
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof CandidateManagerApprovalCheckExistence) {
            throw new UnexpectedTypeException($constraint, CandidateManagerApprovalCheckExistence::class);
        }
            if (!empty($this->managerApprovalExistenceLogic->existence($constraint->candidateLink, $constraint->candidateVacancy))) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }

    }
}