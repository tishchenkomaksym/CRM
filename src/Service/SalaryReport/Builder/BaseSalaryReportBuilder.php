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
use App\Service\User\Sdt\Filter\AtOwnExpenseFilter;
use App\Service\User\Sdt\Filter\NotAtOwnExpenseFilter;
use App\Service\User\Sdt\UsedSdtDaysCalculator;
use DateTime;
use Exception;
use RuntimeException;

class BaseSalaryReportBuilder
{
    private $workingDaysCalculator;
    /**
     * @var SdtDaysCalculator
     */
    private $sdtDaysCalculator;
    /**
     * @var UsedSdtDaysCalculator
     */
    private $usedSdtDaysCalculator;

    public function __construct(
        WorkingDaysCalculator $workingDaysCalculator,
        SdtDaysCalculator $sdtDaysCalculator,
        UsedSdtDaysCalculator $usedSdtDaysCalculator
    ) {
        $this->workingDaysCalculator = $workingDaysCalculator;
        $this->sdtDaysCalculator = $sdtDaysCalculator;
        $this->usedSdtDaysCalculator = $usedSdtDaysCalculator;
    }

    /**
     * @param SalaryReportInfo $previousReportInfo
     * @param SalaryReportInfo $newReport
     * @param User $user
     * @return SalaryReportDTO
     * @throws Exception
     */
    public function build(
        SalaryReportInfo $previousReportInfo,
        SalaryReportInfo $newReport,
        User $user
    ): SalaryReportDTO {

        $returnObject = new SalaryReportDTO();
        $dateTime = new DateTime();
        /** @noinspection NullPointerExceptionInspection */
        $dateTime->setTimestamp($newReport->getCreateDate()->getTimestamp());
        $dateTime->setDate($dateTime->format('Y'), $dateTime->format('m'), (int)$dateTime->format('d') - 1);
        $dateTime->setTime(23, 59, 59);
        $returnObject->sdtCountUsed = $this->getSdtCountUsed($previousReportInfo, $dateTime, $user);
        $returnObject->sdtCountAtOwnExpenseUsed = $this->getSdtAtOwnExpenseUsedCount($previousReportInfo, $dateTime,
            $user);
        $returnObject->calendarWorkingDays = $this->workingDaysCalculator->calculate($newReport) - $returnObject->sdtCountUsed - $returnObject->sdtCountAtOwnExpenseUsed;
        $returnObject->sdtCount = $this->sdtDaysCalculator->calculate($dateTime, $user);


        $returnObject->user = $user;
        return $returnObject;
    }

    /**
     * @param SalaryReportInfo $previousReportInfo
     * @param DateTime $nowTime
     * @param User $user
     * @return int
     * @throws Exception
     */
    private function getSdtCountUsed(
        SalaryReportInfo $previousReportInfo,
        DateTime $nowTime,
        User $user
    ): int {
        $createDate = $previousReportInfo->getCreateDate();
        if (!isset($createDate)) {
            throw new RuntimeException('no date');
        }
        return $this->usedSdtDaysCalculator->calculate(new DateTime("@{$createDate->getTimeStamp()}"),
            $nowTime, (new NotAtOwnExpenseFilter())->filter($user->getSdt()->toArray()));

    }

    /**
     * @param SalaryReportInfo $previousReport
     * @param DateTime $nowTime
     * @param User $user
     * @return int
     * @throws Exception
     */
    private function getSdtAtOwnExpenseUsedCount(
        SalaryReportInfo $previousReport,
        DateTime $nowTime,
        User $user
    ): int {
        $createDate = $previousReport->getCreateDate();
        if (!isset($createDate)) {
            throw new RuntimeException('no date');
        }
        return $this->usedSdtDaysCalculator->calculate(new DateTime("@{$createDate->getTimeStamp()}"),
            $nowTime, (new AtOwnExpenseFilter())->filter($user->getSdt()->toArray()));

    }
}