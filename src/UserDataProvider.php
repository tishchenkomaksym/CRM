<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 16.01.2019
 * Time: 16:29
 */

namespace App;

use App\Service\ElasticSearchClient;

class UserDataProvider
{
    /**
     * @var ElasticSearchClient
     */
    private $elasticSearchClient;

    /**
     * UserDataProvider constructor.
     * @param ElasticSearchClient $elasticSearchClient
     */
    public function __construct(ElasticSearchClient $elasticSearchClient)
    {

        $this->elasticSearchClient = $elasticSearchClient;
    }

    public function getUserTime($user, $dateFrom, $dateTo)
    {
        return $this->elasticSearchClient->getWorkLogFromDate($user,$dateFrom,$dateTo);
    }
}
