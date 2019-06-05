<?php


namespace App\Form\SDT\FormValidators;


use App\Constants\UserRoles;
use App\Entity\Sdt;
use App\Repository\SalaryReportInfoRepository;
use App\Service\Sdt\Create\FormValidators\SdtCount;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UpdateDateValidator extends ConstraintValidator
{

    public $message = 'You don\'t have enough SDT.';
    /**
     * @var SalaryReportInfoRepository
     */
    private $salaryReportInfoRepository;
    /**
     * @var Security
     */
    private $security;


    public function __construct(SalaryReportInfoRepository $salaryReportInfoRepository, Security $security)
    {
        $this->salaryReportInfoRepository = $salaryReportInfoRepository;
        $this->security = $security;
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     * @throws NonUniqueResultException
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof UpdateDate) {
            throw new UnexpectedTypeException($constraint, SdtCount::class);
        }

        if (!$value instanceof Sdt) {
            throw new UnexpectedTypeException($constraint, Sdt::class);
        }

        if ($todaySalaryReport = $this->salaryReportInfoRepository->getTodaySalaryReport()) {
            $todaySalaryReport = $todaySalaryReport->getCreateDate();
            if ($this->security->isGranted([UserRoles::ROLE_TOM]) === false && $value->getCreateDate() <= $todaySalaryReport) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
        }
    }
}