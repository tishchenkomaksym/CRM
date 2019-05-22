<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 12.03.2019
 * Time: 20:24
 */

namespace App\Service\User\PhpDeveloper\Hours;

use App\Entity\User;
use App\Repository\SalaryReportInfoRepository;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;

class ReportWorkHoursBuilderDecorator
{
    /**
     * @var BaseWorkHoursInformationBuilder
     */
    private $builder;

    /**
     * @var DateTime
     */
    private $salaryReportDate;

    /**
     * ReportWorkHoursBuilderDecorator constructor.
     * @param BaseWorkHoursInformationBuilder $builder
     * @param SalaryReportInfoRepository $infoRepository
     * @throws NonUniqueResultException
     */
    public function __construct(BaseWorkHoursInformationBuilder $builder, SalaryReportInfoRepository $infoRepository)
    {
        $this->builder = $builder;
        $createDate = $infoRepository->getTodaySalaryReport();
        if ($createDate) {
            $dateTime = new DateTime();
            if ($createDate->getCreateDate()) {
                $dateTime->setTimestamp($createDate->getCreateDate()->getTimestamp());
                $this->salaryReportDate = $dateTime;
            }
        }
    }

    /**
     * @param User $user
     * @param DateTime $nowDate
     * @return WorkHoursInformation
     */
    public function build(User $user, DateTime $nowDate): WorkHoursInformation
    {
        return $this->builder->build(
            $this->salaryReportDate,
            $nowDate,
            $user
        );
    }

    /**
     * @return DateTime
     */
    public function getSalaryReportDate(): DateTime
    {
        return $this->salaryReportDate;
    }
}
