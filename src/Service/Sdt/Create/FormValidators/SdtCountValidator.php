<?php


namespace App\Service\Sdt\Create\FormValidators;

use App\Entity\Sdt;
use App\Service\User\Sdt\LeftSdtCalculator;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SdtCountValidator extends ConstraintValidator
{
    public $message = 'You don\'t have enough SDT.';
    /**
     * @var LeftSdtCalculator
     */
    private $leftSdtCalculator;

    public function __construct(LeftSdtCalculator $leftSdtCalculator)
    {
        $this->leftSdtCalculator = $leftSdtCalculator;
    }

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
        if (!$value->getAtOwnExpense() && $value->getCount() > $this->leftSdtCalculator->calculate($value->getUser())) {
            $this->context->buildViolation($this->message)
                ->addViolation();
        }
    }
}