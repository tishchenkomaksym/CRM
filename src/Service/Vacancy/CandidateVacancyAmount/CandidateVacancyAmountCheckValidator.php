<?php


namespace App\Service\Vacancy\CandidateVacancyAmount;


use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CandidateVacancyAmountCheckValidator extends ConstraintValidator
{
    /**
     * @var ObjectManager
     */
    private $amountCheckLogic;

    public function __construct(CandidateVacancyAmountCheckLogic $amountCheckLogic)
    {
        $this->amountCheckLogic = $amountCheckLogic;
    }

    public function validate($value, Constraint $constraint):void
    {
        if (!$constraint instanceof CandidateVacancyAmountCheck) {
            throw new UnexpectedTypeException($constraint, CandidateVacancyAmountCheck::class);
        }

        if (count($this->amountCheckLogic->checkCandidateVacancyAmount($constraint->getVacancy())) >= $constraint->getVacancy()->getAmount()) {

            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}