<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 14.03.2019
 * Time: 17:44
 */

namespace App\Service\User\PhpDeveloperLevel\ProjectEffectiveTime;

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
     */
    public function build($user): array
    {
        /** @var UserToProjectTimeSpendDTO[] $array */
        $array = [];
        $projects = $this->searchClient->getEffectiveTimePerUserPerProjects($user);
        foreach ($projects as $project) {
            $array[] = $item = new UserToProjectTimeSpendDTO();
            $item->projectTitle = $project['key'];
            $item->spendTime = $project['effectiveTime']['value'];
        }
        return $array;
    }
}
