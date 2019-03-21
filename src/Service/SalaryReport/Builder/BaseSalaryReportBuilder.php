<?php
/**
 * Created by PhpStorm.
 * User: rubay
 * Date: 3/20/2019
 * Time: 6:51 PM
 */

namespace App\Service\SalaryReport\Builder;


use App\Entity\SalaryReportInfo;
use App\Entity\User;
use App\Service\SalaryReport\Builder\SDTDays\SdtDaysCalculator;
use App\Service\SalaryReport\Builder\WorkingDays\WorkingDaysCalculator;
use App\Service\SalaryReport\SalaryReportDTO;

class BaseSalaryReportBuilder
{
    private $workingDaysCalculator;
    /**
     * @var SdtDaysCalculator
     */
    private $sdtDaysCalculator;

    public function __construct(WorkingDaysCalculator $workingDaysCalculator, SdtDaysCalculator $sdtDaysCalculator)
    {
        $this->workingDaysCalculator = $workingDaysCalculator;
        $this->sdtDaysCalculator = $sdtDaysCalculator;
    }

    /**
     * @param SalaryReportInfo $newReport
     * @param User $user
     * @return SalaryReportDTO
     * @throws \Exception
     */
    public function build(SalaryReportInfo $newReport, User $user): SalaryReportDTO
    {
        $returnObject = new SalaryReportDTO();
        $returnObject->calendarWorkingDays = $this->workingDaysCalculator->calculate($newReport);
        $dateTime = new \DateTime();
        /** @noinspection NullPointerExceptionInspection */
        $dateTime->setTimestamp($newReport->getCreateDate()->getTimestamp());
        $returnObject->sdtCount = $this->sdtDaysCalculator->calculate($dateTime, $user);
        return $returnObject;
    }

}