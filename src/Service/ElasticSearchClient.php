<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 16.01.2019
 * Time: 15:36
 */

namespace App\Service;

use Elasticsearch\ClientBuilder;

class ElasticSearchClient
{
    private $client;

    /**
     * ElasticSearchClient constructor.
     * @param ClientBuilder $clientBuilder
     * @param $host
     */
    public function __construct(ClientBuilder $clientBuilder, $host)
    {
        $this->client = $clientBuilder->setHosts([$host])->build();
    }

    public function getWorkLogFromDate($user, $dateFrom, $dateTo)
    {
        $params = [
            'index' => 'worklogs',
            'type' => 'worklog',
            'scroll' => '1s',          // how long between scroll requests. should be small!
            'size' => 1000,
            'body' => [
//                'sort' => [
//                    'assignee.keyword' => ['order' => 'asc'],
//                    'taskGroup.title.keyword' => ['order' => 'asc'],
//                    'capitalisationTitle.keyword' => ['order' => 'asc']
//                ],
//                'stored_fields' => ['assignee', 'taskGroup.title', 'capitalisationTitle'],
                'query' => [
//                    'range' => [
//                        'started' => ['gte' => $dateFrom, 'lte' => $dateTo, 'format' => 'dd/MM/yyyy||yyyy']
//                    ],
                    'match' => [
                        'author.userName' => $user
                    ]
                ],
                'aggs' => [
                    'time' => [
                        'sum' => [
                            'field' => 'time'
                        ]
                    ]
                ]
            ]
        ];
        return $this->client->search($params);
    }
}
