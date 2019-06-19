<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 31.01.2019
 * Time: 12:02
 */

namespace App\Data\Sdt\Mail\Adapter;

use App\Calendar\DateCalculator\UserSubTeamDateCalculator;
use App\Data\Sdt\Mail\NewSdtMailData;
use App\Entity\Sdt;
use App\Repository\SDTEmailAssigneeRepository;
use App\Repository\UserInfoRepository;
use App\Service\HolidayService;
use Exception;

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
     * @param UserSubTeamDateCalculator $userSubTeamDateCalculator
     * @return NewSdtMailData
     * @throws NoDateException
     * @throws Exception
     */
    public function getNewSdtMail(
        Sdt $sdt,
        HolidayService $holidayService,
        UserInfoRepository $userInfoRepository,
        UserSubTeamDateCalculator $userSubTeamDateCalculator
    ): NewSdtMailData {
        $createDate = $sdt->getCreateDate();
        $emails = [];

        foreach ($this->SDTEmailAssigneeRepository->findBy(['user' => $sdt->getUser()->getId()]) as $email) {
            $emails[] = $email->getEmail();
        }
        if ($createDate !== null) {
            $userInfo = $userInfoRepository->findOneBy(['user' => $sdt->getUser()->getId()]);
            $endDate = $userSubTeamDateCalculator->getDateWithOffset($userInfo, $sdt, $holidayService);

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
