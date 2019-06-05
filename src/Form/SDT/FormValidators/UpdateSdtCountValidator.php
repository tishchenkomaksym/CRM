<?php


namespace App\Form\SDT\FormValidators;


use App\Entity\Sdt;
use App\Service\Sdt\Create\FormValidators\SdtCount;
use App\Service\User\Sdt\LeftSdtCalculator;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UpdateSdtCountValidator extends ConstraintValidator
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
        if (!$constraint instanceof UpdateSdtCount) {
            throw new UnexpectedTypeException($constraint, SdtCount::class);
        }

        if (!$value instanceof Sdt) {
            throw new UnexpectedTypeException($constraint, Sdt::class);
        }

        $newLeftSdt = $this->leftSdtCalculator->calculate($value->getUser());

        if ($newLeftSdt < 0 && !$value->getAtOwnExpense()){
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}