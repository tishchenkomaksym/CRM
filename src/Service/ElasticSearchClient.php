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

    public function getTimePerComponent($component, $userName)
    {
        $params = [
            'index' => 'worklogs',
            'type' => 'worklog',
            'size' => 0,
            'body' => [
                'query' => [
                    'constant_score' => [
                        'filter' => [
                            'bool' =>
                                [
                                    'must' => [
                                        [
                                            'match' => ['author.userName' => $userName],
                                        ],
                                        [
                                            'match' => ['technicalComponents' => $component],
                                        ],
                                    ]
                                ]
                        ]
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
        $data = $this->client->search($params);
        return $data['aggregations']['time']['value'];
    }
}
