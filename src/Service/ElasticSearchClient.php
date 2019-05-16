<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 16.01.2019
 * Time: 15:36
 */

namespace App\Service;

use DateTime;
use Elasticsearch\ClientBuilder;
use Exception;

class ElasticSearchClient
{
    public const MATCH = 'match';

    public const DEFAULT_DATE_FORMAT = 'Y-m-d';
    public const DEFAULT_ELASTIC_DATE_FORMAT = 'yyyy-MM-dd';

    public const FIELD_EFFECTIVE_TIME = 'effectiveTime';
    public const FIELD_COMPONENTS_EFFECTIVE_TIME = 'technicalComponentsEffectiveTime';
    public const FIELD_TIME = 'time';
    public const FIELD_AUTHOR_USER_NAME = 'author.userName.keyword';

    public const ELASTIC_INDEX_FIELD = 'index';
    public const ELASTIC_TYPE_FIELD = 'type';
    public const ELASTIC_QUERY_FIELD = 'query';
    public const ELASTIC_VALUE_FIELD = 'value';
    public const ELASTIC_CONSTANT_FIELD = 'constant_score';
    public const ELASTIC_FILTER_FIELD = 'filter';
    public const ELASTIC_RANGE_FIELD = 'range';
    public const ELASTIC_FORMAT_FIELD = 'format';
    public const ELASTIC_FIELD_FIELD = 'field';
    public const ELASTIC_STARTED_FIELD = 'started';
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
                                        [
                                            self::MATCH => ['issueStatus' => 'Closed'],
                                        ],
                                    ]
                                ]
                        ]
                    ]
                ],
                'aggs' => [
                    self::FIELD_EFFECTIVE_TIME => [
                        'sum' => [
                            self::ELASTIC_FIELD_FIELD => self::FIELD_COMPONENTS_EFFECTIVE_TIME
                        ]
                    ]
                ]
            ]
        ];
        $data = $this->client->search($params);
        if (empty($data[self::ELASTIC_AGGREGATIONS_FIELD][self::FIELD_EFFECTIVE_TIME][self::ELASTIC_VALUE_FIELD])) {
            return 0;
        }
        return $data[self::ELASTIC_AGGREGATIONS_FIELD][self::FIELD_EFFECTIVE_TIME][self::ELASTIC_VALUE_FIELD];
    }

    public function getTimeFromDateToDate(DateTime $from, DateTime $to, $userName)
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
                                self::ELASTIC_RANGE_FIELD => [
                                    self::ELASTIC_STARTED_FIELD => [
                                        'gte' => $from->format(self::DEFAULT_DATE_FORMAT),
                                        'lte' => $to->format(self::DEFAULT_DATE_FORMAT),
                                        self::ELASTIC_FORMAT_FIELD => self::DEFAULT_ELASTIC_DATE_FORMAT
                                    ]
                                ]
                            ]
                        ],

                ],
                'aggs' => [
                    self::FIELD_TIME => [
                        'sum' => [
                            self::ELASTIC_FIELD_FIELD => self::FIELD_TIME
                        ]
                    ]
                ]
            ]
        ];
        $data = $this->client->search($params);
        if (empty($data[self::ELASTIC_AGGREGATIONS_FIELD][self::FIELD_TIME][self::ELASTIC_VALUE_FIELD])) {
            return 0;
        }
        return $data[self::ELASTIC_AGGREGATIONS_FIELD][self::FIELD_TIME][self::ELASTIC_VALUE_FIELD];
    }


    /**
     * @param string $userName
     * @param DateTime $startDate
     * @return float
     * @throws Exception
     */
    public function getEffectiveTimePerUserDate(string $userName, DateTime $startDate): float
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
                                            self::MATCH => ['issueStatus' => 'Closed'],
                                        ],
                                    ],
                                    self::ELASTIC_FILTER_FIELD => [
                                        self::ELASTIC_RANGE_FIELD => [
                                            self::ELASTIC_STARTED_FIELD => [
                                                'gte' => $startDate->format(self::DEFAULT_DATE_FORMAT),
                                                'lte' => (new DateTime())->format(self::DEFAULT_DATE_FORMAT),
                                                self::ELASTIC_FORMAT_FIELD => self::DEFAULT_ELASTIC_DATE_FORMAT
                                            ]
                                        ]
                                    ]
                                ]
                        ]
                    ]
                ],
                'aggs' => [
                    self::FIELD_EFFECTIVE_TIME => [
                        'sum' => [
                            self::ELASTIC_FIELD_FIELD => self::FIELD_EFFECTIVE_TIME
                        ]
                    ]
                ]
            ]
        ];
        $data = $this->client->search($params);
        if (empty($data[self::ELASTIC_AGGREGATIONS_FIELD][self::FIELD_EFFECTIVE_TIME][self::ELASTIC_VALUE_FIELD])) {
            return 0;
        }
        return $data[self::ELASTIC_AGGREGATIONS_FIELD][self::FIELD_EFFECTIVE_TIME][self::ELASTIC_VALUE_FIELD];
    }

    /**
     * @param string $userName
     * @param DateTime $startDate
     * @return array
     * @throws Exception
     */
    public function getEffectiveTimePerUserPerProjects(string $userName, DateTime $startDate)
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
                                    self::ELASTIC_FILTER_FIELD => [
                                        [self::ELASTIC_RANGE_FIELD => [
                                            self::ELASTIC_STARTED_FIELD => [
                                                'gte' => $startDate->format(self::DEFAULT_DATE_FORMAT),
                                                'lte' => (new DateTime())->format(self::DEFAULT_DATE_FORMAT),
                                                self::ELASTIC_FORMAT_FIELD => self::DEFAULT_ELASTIC_DATE_FORMAT
                                            ]
                                        ]],
                                        [
                                            'term' => [
                                                'taskGroup.components.keyword' => 'Bonus project',
                                            ]
                                        ],
                                        [
                                            ['term' => [
                                                'author.userName.keyword' => $userName,
                                            ]
                                            ]
                                        ]
                                    ]
                                ]
                        ]
                    ]
                ],
                'aggs' => [
                    'project' => [
                        'terms' => [self::ELASTIC_FIELD_FIELD => 'taskGroup.title.keyword', 'size' => 10000],
                        'aggs' => [
                            self::FIELD_EFFECTIVE_TIME => [
                                'sum' => [
                                    self::ELASTIC_FIELD_FIELD => self::FIELD_EFFECTIVE_TIME
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
                            self::ELASTIC_FIELD_FIELD => 'time'
                        ]
                    ]
                ]
            ]
        ];
        $data = $this->client->search($params);
        return $data[self::ELASTIC_AGGREGATIONS_FIELD]['effectiveTime'][self::ELASTIC_VALUE_FIELD];
    }
}
