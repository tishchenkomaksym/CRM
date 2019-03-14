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
    public const FIELD_AUTHOR_USER_NAME = 'author.userName.keyword';

    public const ELASTIC_INDEX_FIELD = 'index';
    public const ELASTIC_TYPE_FIELD = 'type';
    public const ELASTIC_QUERY_FIELD = 'query';
    public const ELASTIC_CONSTANT_FIELD = 'constant_score';
    public const ELASTIC_FILTER_FIELD = 'filter';
    public const ELASTIC_AGGREGATIONS_FIELD = 'aggregations';
    public const INDEX_WORK_LOGS_NAME = 'worklogs';
    public const INDEX_TYPE_WORK_LOG_NAME = 'worklog';
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
            self::ELASTIC_INDEX_FIELD => self::INDEX_WORK_LOGS_NAME,
            self::ELASTIC_TYPE_FIELD => self::INDEX_TYPE_WORK_LOG_NAME,
            'size' => 0,
            'body' => [
                self::ELASTIC_QUERY_FIELD => [
                    self::ELASTIC_CONSTANT_FIELD => [
                        self::ELASTIC_FILTER_FIELD => [
                            'bool' =>
                                [
                                    'must' => [
                                        [
                                            self::MATCH => [self::FIELD_AUTHOR_USER_NAME => $userName],
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
        if (empty($data[self::ELASTIC_AGGREGATIONS_FIELD][self::FIELD_EFFECTIVE_TIME]['value'])) {
            return 0;
        }
        return $data[self::ELASTIC_AGGREGATIONS_FIELD][self::FIELD_EFFECTIVE_TIME]['value'];
    }

    public function getTimeFromDateToDate(\DateTime $from, \DateTime $to, $userName)
    {
        $params = [
            self::ELASTIC_INDEX_FIELD => self::INDEX_WORK_LOGS_NAME,
            self::ELASTIC_TYPE_FIELD => self::INDEX_TYPE_WORK_LOG_NAME,
            'size' => 0,
            'body' => [
                self::ELASTIC_QUERY_FIELD => [
                    'bool' =>
                        [
                            'must' => [
                                [
                                    self::MATCH => [self::FIELD_AUTHOR_USER_NAME => $userName],
                                ],
                            ],
                            self::ELASTIC_FILTER_FIELD => [
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
        if (empty($data[self::ELASTIC_AGGREGATIONS_FIELD][self::FIELD_TIME]['value'])) {
            return 0;
        }
        return $data[self::ELASTIC_AGGREGATIONS_FIELD][self::FIELD_TIME]['value'];
    }


    public function getEffectiveTimePerUser(string $userName)
    {
        $params = [
            self::ELASTIC_INDEX_FIELD => self::INDEX_WORK_LOGS_NAME,
            self::ELASTIC_TYPE_FIELD => self::INDEX_TYPE_WORK_LOG_NAME,
            'size' => 0,
            'body' => [
                self::ELASTIC_QUERY_FIELD => [
                    self::ELASTIC_CONSTANT_FIELD => [
                        self::ELASTIC_FILTER_FIELD => [
                            'bool' =>
                                [
                                    'must' => [
                                        [
                                            self::MATCH => [self::FIELD_AUTHOR_USER_NAME => $userName],
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
        if (empty($data[self::ELASTIC_AGGREGATIONS_FIELD][self::FIELD_EFFECTIVE_TIME]['value'])) {
            return 0;
        }
        return $data[self::ELASTIC_AGGREGATIONS_FIELD][self::FIELD_EFFECTIVE_TIME]['value'];
    }

    public function getEffectiveTimePerUserPerProjects(string $userName)
    {
        $params = [
            self::ELASTIC_INDEX_FIELD => self::INDEX_WORK_LOGS_NAME,
            self::ELASTIC_TYPE_FIELD => self::INDEX_TYPE_WORK_LOG_NAME,
            'size' => 0,
            'body' => [
                self::ELASTIC_QUERY_FIELD => [
                    self::ELASTIC_CONSTANT_FIELD => [
                        self::ELASTIC_FILTER_FIELD => [
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
                    'project' => [
                        'terms' => ['field' => 'taskGroup.title.keyword', 'size' => 10000],
                        'aggs' => [
                            self::FIELD_EFFECTIVE_TIME => [
                                'sum' => [
                                    'field' => self::FIELD_EFFECTIVE_TIME
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $data = $this->client->search($params);
        return $data[self::ELASTIC_AGGREGATIONS_FIELD]['project']['buckets'];
    }

    public function getWorkLogTimePerDateRange($userName)
    {
        $params = [
            self::ELASTIC_INDEX_FIELD => self::INDEX_WORK_LOGS_NAME,
            self::ELASTIC_TYPE_FIELD => self::INDEX_TYPE_WORK_LOG_NAME,
            'size' => 0,
            'body' => [
                self::ELASTIC_QUERY_FIELD => [
                    self::ELASTIC_CONSTANT_FIELD => [
                        self::ELASTIC_FILTER_FIELD => [
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
        return $data[self::ELASTIC_AGGREGATIONS_FIELD]['effectiveTime']['value'];
    }
}
