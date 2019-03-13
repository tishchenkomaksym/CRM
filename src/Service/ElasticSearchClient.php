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
    public const MATCH='match';
    public const FIELD_EFFECTIVE_TIME='effectiveTime';
    public const FIELD_TIME = 'time';
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

    public function getTimePerComponent(string $component, string $userName)
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
                                            self::MATCH => ['author.userName' => $userName],
                                        ],
                                        [
                                            self::MATCH => ['technicalComponents' => $component],
                                        ],
                                    ]
                                ]
                        ]
                    ]
                ],
                'aggs' => [
                    self::FIELD_EFFECTIVE_TIME => [
                        'sum' => [
                            'field' => self::FIELD_EFFECTIVE_TIME
                        ]
                    ]
                ]
            ]
        ];
        $data = $this->client->search($params);
        if (empty($data['aggregations'][self::FIELD_EFFECTIVE_TIME]['value'])) {
            return 0;
        }
        return $data['aggregations'][self::FIELD_EFFECTIVE_TIME]['value'];
    }

    public function getTimeFromDateToDate(\DateTime $from, \DateTime $to, $userName)
    {
        $params = [
            'index' => 'worklogs',
            'type' => 'worklog',
            'size' => 0,
            'body' => [
                'query' => [
                    'bool' =>
                        [
                            'must' => [
                                [
                                    self::MATCH => ['author.userName' => $userName],
                                ],
                            ],
                            'filter' => [
                                'range' => [
                                    'started' => [
                                        'gte' => $from->format('Y-m-d'),
                                        'lte' => $to->format('Y-m-d'),
                                        'format'=>'yyyy-MM-dd'
                                    ]
                                ]
                            ]
                        ],

                ],
                'aggs' => [
                    self::FIELD_TIME => [
                        'sum' => [
                            'field' => self::FIELD_TIME
                        ]
                    ]
                ]
            ]
        ];
        $data = $this->client->search($params);
        if (empty($data['aggregations'][self::FIELD_TIME]['value'])) {
            return 0;
        }
        return $data['aggregations'][self::FIELD_TIME]['value'];
    }


    public function getEffectiveTimePerUser(string $userName)
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
                                            self::MATCH => ['author.userName' => $userName],
                                        ],
                                    ]
                                ]
                        ]
                    ]
                ],
                'aggs' => [
                    self::FIELD_EFFECTIVE_TIME => [
                        'sum' => [
                            'field' => self::FIELD_EFFECTIVE_TIME
                        ]
                    ]
                ]
            ]
        ];
        $data = $this->client->search($params);
        if (empty($data['aggregations'][self::FIELD_EFFECTIVE_TIME]['value'])) {
            return 0;
        }
        return $data['aggregations'][self::FIELD_EFFECTIVE_TIME]['value'];
    }

    public function getWorkLogTimePerDateRange($userName)
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
                                            self::MATCH => ['author.userName' => $userName],
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
        return $data['aggregations']['effectiveTime']['value'];
    }
}
