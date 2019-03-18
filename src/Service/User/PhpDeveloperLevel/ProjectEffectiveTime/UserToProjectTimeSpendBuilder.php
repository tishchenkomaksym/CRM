<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 14.03.2019
 * Time: 17:44
 */

namespace App\Service\User\PhpDeveloperLevel\ProjectEffectiveTime;

use App\Entity\User;
use App\Service\ElasticSearchClient;

class UserToProjectTimeSpendBuilder
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
     * @param $user
     * @return UserToProjectTimeSpendDTO[]
     * @throws \Exception
     */
    public function build(User $user): array
    {
        /** @var UserToProjectTimeSpendDTO[] $array */
        $array = [];
        $startDate = new \DateTime();
        $startDate->setDate(2014, 1, 1);
        $startDateAndValues = $user->getPhpDeveloperStartTimeAndDateValue();
        if (($startDateAndValues !== null) && $startDateAndValues->getCreateDate()) {
            $startDate->setTimestamp($startDateAndValues->getCreateDate()->getTimestamp());
        }
        $projects = $this->searchClient->getEffectiveTimePerUserPerProjects($user, $startDate);
        foreach ($projects as $project) {
            $array[] = $item = new UserToProjectTimeSpendDTO();
            $item->projectTitle = $project['key'];
            $item->spendTime = $project['effectiveTime']['value'];
        }
        return $array;
    }
}
