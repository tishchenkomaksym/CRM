<?php


namespace App\Service\Sdt\Create\FormValidators;

use App\Entity\Sdt;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SdtCountValidator extends ConstraintValidator
{
    public $message = 'You don\'t have enough SDT.';

    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof SdtCount) {
            throw new UnexpectedTypeException($constraint, SdtCount::class);
        }

        if (!$value instanceof Sdt) {
            throw new UnexpectedTypeException($constraint, Sdt::class);
        }
        if (!$value->getAtOwnExpense() && $value->getCount() <= $constraint->getLeftSdtCalculator()->calculate($value->getUser())) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}