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
use App\Repository\SalaryReportInfoRepository;
use App\Service\SalaryReport\Builder\SDTDays\SdtDaysCalculator;
use App\Service\SalaryReport\Builder\WorkingDays\WorkingDaysCalculator;
use App\Service\SalaryReport\SalaryReportDTO;
use App\Service\User\Sdt\UsedSdtDaysCalculator;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Exception;

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
    /**
     * @var SalaryReportInfoRepository
     */
    private $salaryReportInfoRepository;


    public function __construct(
        SalaryReportInfoRepository $salaryReportInfoRepository,
        WorkingDaysCalculator $workingDaysCalculator,
        SdtDaysCalculator $sdtDaysCalculator,
        UsedSdtDaysCalculator $usedSdtDaysCalculator)
    {
        $this->workingDaysCalculator = $workingDaysCalculator;
        $this->sdtDaysCalculator = $sdtDaysCalculator;
        $this->usedSdtDaysCalculator = $usedSdtDaysCalculator;
        $this->salaryReportInfoRepository = $salaryReportInfoRepository;
    }

    /**
     * @param SalaryReportInfo $newReport
     * @param User $user
     * @return SalaryReportDTO
     * @throws Exception
     */
    public function build(SalaryReportInfo $newReport, User $user): SalaryReportDTO
    {

        $returnObject = new SalaryReportDTO();
        $dateTime = new DateTime();
        /** @noinspection NullPointerExceptionInspection */
        $dateTime->setTimestamp($newReport->getCreateDate()->getTimestamp());
        $returnObject->sdtCountUsed = $this->getSdtCountUsed($newReport, $dateTime, $user);
        $returnObject->calendarWorkingDays = $this->workingDaysCalculator->calculate($newReport) - $returnObject->sdtCountUsed;
        $returnObject->sdtCount = $this->sdtDaysCalculator->calculate($dateTime, $user);

        $returnObject->user = $user;
        return $returnObject;
    }

    /**
     * @param SalaryReportInfo $newReport
     * @param DateTime $nowTime
     * @param User $user
     * @return int
     * @throws NonUniqueResultException
     */
    private function getSdtCountUsed(SalaryReportInfo $newReport, DateTime $nowTime, User $user): int
    {
        $previousReport = $this->salaryReportInfoRepository->getPreviousReport($newReport);
        /** @noinspection PhpParamsInspection */
        return $this->usedSdtDaysCalculator->calculate($previousReport->getCreateDate(), $nowTime, $user);
    }
}