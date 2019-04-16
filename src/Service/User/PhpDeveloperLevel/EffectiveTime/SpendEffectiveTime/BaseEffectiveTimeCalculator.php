<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 18.03.2019
 * Time: 11:08
 */

namespace App\Service\User\PhpDeveloperLevel\EffectiveTime\SpendEffectiveTime;

use App\Entity\User;
use App\Service\ElasticSearchClient;
use App\Service\UserInformationService;

class BaseEffectiveTimeCalculator
{
    /**
     * @var ElasticSearchClient
     */
    private $searchClient;

    public function __construct(ElasticSearchClient $searchClient)
    {
        $this->searchClient = $searchClient;
    }

    /**
     * @param User $user
     * @return float
     * @throws \Exception
     */
    public function calculate(User $user): float
    {
        /** @var float $effectiveTime */
        $effectiveTime = 0;
        $startDate = new \DateTime();
        $startDate->setDate(2014, 01, 01);


        $startDateAndValues = $user->getPhpDeveloperStartTimeAndDateValue();
        if ($startDateAndValues !== null) {
            if($startDateAndValues->getCreateDate())
            {
                $startDate = new \DateTime();
                $startDate->setTimestamp($startDateAndValues->getCreateDate()->getTimestamp());
            }
            $effectiveTime = $startDateAndValues->getEffectiveTime();
        }
        return $effectiveTime + $this->searchClient
                ->getEffectiveTimePerUserDate(UserInformationService::getSystemName($user), $startDate);

    }
}
