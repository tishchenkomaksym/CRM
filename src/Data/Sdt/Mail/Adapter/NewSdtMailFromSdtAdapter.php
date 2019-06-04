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
use App\Data\Sdt\Mail\NewSdtMailData;
use App\Entity\Sdt;
use App\Repository\SDTEmailAssigneeRepository;
use App\Repository\UserInfoRepository;
use App\Service\HolidayService;
use Facebook\WebDriver\Exception\NullPointerException;

class NewSdtMailFromSdtAdapter
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
     * @return NewSdtMailData
     * @throws NoDateException
     */
    public  function getNewSdtMail(Sdt $sdt, HolidayService $holidayService, UserInfoRepository $userInfoRepository): NewSdtMailData

    {
        $createDate = $sdt->getCreateDate();
        $emails = [];

        foreach ($this->SDTEmailAssigneeRepository->findBy(['user' => $sdt->getUser()->getId()]) as $email) {
            $emails[] = $email->getEmail();
        }
        if ($createDate !== null) {
            $userInfo = $userInfoRepository->findOneBy(['user' => $sdt->getUser()->getId()]);
            /** @noinspection NullPointerExceptionInspection */
            if ($userInfo->getSubTeam() === 'Central Tech Support') {
                $endDate = BaseDateCalculator::getDateWithOffset($createDate, $sdt->getCount());
            } else {
                $endDate = DateCalculatorWithWeekends::getDateWithOffset($createDate, $sdt->getCount(),
                    $holidayService);
            }
            return new NewSdtMailData(
                $sdt->getUser()->getName(),
                $createDate->format('Y-m-d'),
                $endDate->format('Y-m-d'),
                $sdt->getActing(),
                $sdt->getCount(),
                $sdt->getAtOwnExpense(),
                $emails

            );
        }
        throw new NoDateException('Entity has no create date');
    }
}
