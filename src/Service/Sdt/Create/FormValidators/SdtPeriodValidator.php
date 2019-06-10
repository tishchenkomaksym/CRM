<?php


namespace App\Service\Sdt\Create\FormValidators;

use App\Calendar\DateCalculator\BaseDateCalculator;
use App\Calendar\DateCalculator\DateCalculatorWithWeekends;
use App\Entity\Sdt;
use App\Repository\UserInfoRepository;
use App\Service\HolidayService;
use App\Service\Sdt\Interval\EndDateOfSdtCalculator;
use App\Service\UserInformationService;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SdtPeriodValidator extends ConstraintValidator
{
    public $message = 'Sorry, it seems you have already created SDT for selected period. Please, edit or delete previously created SDT';
    /**
     * @var EndDateOfSdtCalculator
     */
    private $endDateOfSdtCalculator;
    /**
     * @var UserInfoRepository
     */
    private $userInfoRepository;
    /**
     * @var HolidayService
     */
    private $holidayService;
    /**
     * @var UserInformationService
     */
    private $userInformationService;

    public function __construct(EndDateOfSdtCalculator $endDateOfSdtCalculator,
        UserInfoRepository $userInfoRepository,
        HolidayService $holidayService,
        UserInformationService $userInformationService)
    {
        $this->endDateOfSdtCalculator = $endDateOfSdtCalculator;
        $this->userInfoRepository = $userInfoRepository;
        $this->holidayService = $holidayService;
        $this->userInformationService = $userInformationService;
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     * @throws \Exception
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof SdtPeriod) {
            throw new UnexpectedTypeException($constraint, SdtPeriod::class);
        }

        if (!$value instanceof Sdt) {
            throw new UnexpectedTypeException($constraint, Sdt::class);
        }
        $userInfo = $this->userInfoRepository->findOneBy(['user' => $value->getUser()->getId()]);
        $newStartDate = $value->getCreateDate();
        $newEndDate = $this->endDateOfSdtCalculator->calculate($value);
        $sdtCollection = $this->userInformationService
            ->getAllUserSdt($value->getUser());
        foreach ($sdtCollection->getItems() as $sdt) {
            $startPeriod = $sdt->getCreateDate();
            if ($userInfo !== null && $userInfo->getSubTeam() === 'Central Tech Support') {
                $endPeriod = BaseDateCalculator::getDateWithOffset($sdt->getCreateDate(), $sdt->getCount());
            } else {
                $endPeriod = DateCalculatorWithWeekends::getDateWithOffset($sdt->getCreateDate(), $sdt->getCount(),
                    $this->holidayService);
            }
            if ($newStartDate === $startPeriod) {
                /** @noinspection NullPointerExceptionInspection */
                $userInfo->getUser()->removeSdt($sdt);
            } elseif (($newStartDate <= $startPeriod && $startPeriod <= $newEndDate && $newEndDate >= $startPeriod) ||
                ($newStartDate >= $startPeriod && $newEndDate <= $endPeriod) ||
                ($newStartDate >= $startPeriod && $newStartDate <= $endPeriod)|| $newStartDate === $endPeriod ||
                $newEndDate === $startPeriod || $newEndDate === $endPeriod) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
        }
    }
}