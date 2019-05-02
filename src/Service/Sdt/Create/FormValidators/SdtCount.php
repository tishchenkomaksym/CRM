<?php


namespace App\Service\Sdt\Create\FormValidators;

use App\Service\User\Sdt\LeftSdtCalculator;
use Symfony\Component\Validator\Constraint;

class SdtCount extends Constraint
{
    public $message = 'You don\'t have enough SDT.';
    /**
     * @var LeftSdtCalculator
     */
    private $leftSdtCalculator;

    public function __construct(LeftSdtCalculator $leftSdtCalculator)
    {
        parent::__construct([]);
        $this->leftSdtCalculator = $leftSdtCalculator;
    }

    /**
     * @return LeftSdtCalculator
     */
    public function getLeftSdtCalculator(): LeftSdtCalculator
    {
        return $this->leftSdtCalculator;
    }
}