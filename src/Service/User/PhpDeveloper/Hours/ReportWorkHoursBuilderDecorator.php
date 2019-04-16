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

class ReportWorkHoursBuilderDecorator
{
    /**
     * @var BaseWorkHoursInformationBuilder
     */
    private $builder;
    /**
     * @var SalaryReportInfoRepository
     */
    private $infoRepository;

    public function __construct(BaseWorkHoursInformationBuilder $builder, SalaryReportInfoRepository $infoRepository)
    {

        $this->builder = $builder;
        $this->infoRepository = $infoRepository;
    }

    /**
     * @param User $user
     * @return WorkHoursInformation
     * @throws \Exception
     */
    public function build(User $user): WorkHoursInformation
    {
        $createDate = $this->infoRepository->findOneBy([], ['createDate' => 'ASC']);
        if ($createDate) {
            $dateTime = new \DateTime();
            if ($createDate->getCreateDate()) {
                $dateTime->setTimestamp($createDate->getCreateDate()->getTimestamp());
            }
            return $this->builder->build(
                $dateTime,
                new DateTime(),
                $user
            );
        }
        //TODO: remove this crutch
        return null;
    }
}
