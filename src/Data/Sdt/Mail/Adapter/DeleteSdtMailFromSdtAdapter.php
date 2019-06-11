<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 31.01.2019
 * Time: 12:02
 */

namespace App\Data\Sdt\Mail\Adapter;

use App\Calendar\DateCalculator\UserSubTeamDateCalculator;
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
     * @param UserSubTeamDateCalculator $userSubTeamDateCalculator
     * @return DeleteSdtMailData
     * @throws \Exception
     * @throws NoDateException
     */
    public function getNewSdtMail(Sdt $sdt,
        HolidayService $holidayService,
        UserInfoRepository $userInfoRepository,
        UserSubTeamDateCalculator $userSubTeamDateCalculator
    ): DeleteSdtMailData
    {
        $createDate = $sdt->getCreateDate();
        if ($createDate !== null) {
            $emails = [];
            foreach ($this->SDTEmailAssigneeRepository->findBy(['user' => $sdt->getUser()->getId()]) as $email) {
                $emails[] = $email->getEmail();
            }
            $userInfo = $userInfoRepository->findOneBy(['user' => $sdt->getUser()->getId()]);
            $endDate = 0;
            if ($userInfo !== null) {
                $endDate = $userSubTeamDateCalculator->getDateWithOffset($userInfo, $sdt, $holidayService);
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
