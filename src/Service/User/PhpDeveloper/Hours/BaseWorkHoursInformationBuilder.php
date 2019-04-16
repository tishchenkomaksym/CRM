<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 12.03.2019
 * Time: 20:24
 */

namespace App\Service\User\PhpDeveloper\Hours;

use App\Data\Sdt\SdtCollection;
use App\Entity\User;
use App\Repository\SdtRepository;
use App\Service\ElasticSearchClient;
use App\Service\UserInformationService;
use App\Service\WorkingDays\BaseWorkingDaysCalculator;
use DateTime;

class BaseWorkHoursInformationBuilder
{
    public const HOURS_IN_WORKING_DAY = 7.5;

    /**
     * @var ElasticSearchClient
     */
    private $searchClient;
    /**
     * @var SdtRepository
     */
    private $sdtRepository;
    /**
     * @var BaseWorkingDaysCalculator
     */
    private $workingDaysCalculator;

    public function __construct(
        ElasticSearchClient $searchClient,
        SdtRepository $sdtRepository,
        BaseWorkingDaysCalculator $workingDaysCalculator
    ) {
        $this->searchClient = $searchClient;
        $this->sdtRepository = $sdtRepository;
        $this->workingDaysCalculator = $workingDaysCalculator;
    }

    /**
     * @param DateTime $from
     * @param DateTime $to
     * @param User $user
     * @return WorkHoursInformation
     */
    public function build(\DateTime $from, DateTime $to, User $user): WorkHoursInformation
    {
        $information = new WorkHoursInformation();
        $information->setRequiredTime($this->getRequiredTimeInHours($from, $to));
        $information->setLoggedTime($this->getLoggedTimeInHours($from, $to, $user));
        $information->setUser($user);
        return $information;
    }

    private function getLoggedTimeInHours(DateTime $from, DateTime $to, User $user)
    {
        $time = $this->searchClient->getTimeFromDateToDate($from, $to, UserInformationService::getSystemName($user));
        $sdtCollection = new SdtCollection($this->sdtRepository->getSDTFromDateToDate($from, $to, $user));
        return $time + $sdtCollection->getCountSum() * self::HOURS_IN_WORKING_DAY;
    }

    private function getRequiredTimeInHours(DateTime $from, DateTime $to)
    {
        return $this->workingDaysCalculator->getWorkingHoursBetweenDates($from,$to);
    }
}
