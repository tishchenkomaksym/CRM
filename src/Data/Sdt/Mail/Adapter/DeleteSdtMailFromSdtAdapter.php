<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 31.01.2019
 * Time: 12:02
 */

namespace App\Data\Sdt\Mail\Adapter;

use App\Calendar\DateCalculator\BaseDateCalculator;
use App\Calendar\DateCalculator\DateCalculatorWithWeekends;
use App\Data\Sdt\Mail\DeleteSdtMailData;
use App\Entity\Sdt;
use App\Repository\SDTEmailAssigneeRepository;
use App\Repository\UserInfoRepository;
use App\Service\HolidayService;

class DeleteSdtMailFromSdtAdapter
{

    /**
     * @var SDTEmailAssigneeRepository
     */
    private $SDTEmailAssigneeRepository;

    public function __construct(SDTEmailAssigneeRepository $SDTEmailAssigneeRepository)
    {
        $this->SDTEmailAssigneeRepository = $SDTEmailAssigneeRepository;
    }

    /**
     * @param Sdt $sdt
     * @param HolidayService $holidayService
     * @param UserInfoRepository $userInfoRepository
     * @return DeleteSdtMailData
     * @throws NoDateException
     */
    public function getNewSdtMail(Sdt $sdt, HolidayService $holidayService, UserInfoRepository $userInfoRepository): DeleteSdtMailData
    {
        $createDate = $sdt->getCreateDate();
        if ($createDate !== null) {
            $emails = [];
            foreach ($this->SDTEmailAssigneeRepository->findBy(['user' => $sdt->getUser()->getId()]) as $email) {
                $emails[] = $email->getEmail();
            }
            $userInfo = $userInfoRepository->findOneBy(['user' => $sdt->getUser()->getId()]);
            if ($userInfo !== null && $userInfo->getSubTeam() === 'Central Tech Support') {
                $endDate = BaseDateCalculator::getDateWithOffset($createDate, $sdt->getCount());
            } else {
                $endDate = DateCalculatorWithWeekends::getDateWithOffset($createDate, $sdt->getCount(), $holidayService);
            }
            return new DeleteSdtMailData(
                $sdt->getUser()->getName(),
                $createDate->format('Y-m-d'),
                $endDate->format('Y-m-d'),
                $sdt->getCount(),
                $emails
            );
        }
        throw new NoDateException('Entity has no create date');
    }
}
