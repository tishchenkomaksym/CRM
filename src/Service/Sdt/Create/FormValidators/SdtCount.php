<?php


namespace App\Service\Sdt\Create\FormValidators;

use App\Entity\User;
use App\Service\User\Sdt\LeftSdtCalculator;
use Symfony\Component\Validator\Constraint;

class SdtCount extends Constraint
{
    public $message = 'You don\'t have enough SDT.';
    /**
     * @var LeftSdtCalculator
     */
    private $leftSdtCalculator;
    /**
     * @var User
     */
    private $user;

    public function __construct(LeftSdtCalculator $leftSdtCalculator, User $user)
    {
        parent::__construct([]);
        $this->leftSdtCalculator = $leftSdtCalculator;
        $this->user = $user;
    }

    /**
     * @return LeftSdtCalculator
     */
    public function getLeftSdtCalculator(): LeftSdtCalculator
    {
        return $this->leftSdtCalculator;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}