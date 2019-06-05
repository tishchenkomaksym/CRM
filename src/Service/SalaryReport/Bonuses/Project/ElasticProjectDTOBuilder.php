<?php


namespace App\Service\SalaryReport\Bonuses\Project;


use App\Service\ElasticSearchClient;
use DateTime;
use Exception;

class ElasticProjectDTOBuilder
{
    /**
     * @var ElasticSearchClient
     */
    private $elasticSearchProjectInfoDataProvider;

    public function __construct(ElasticSearchClient $elasticSearchClient)
    {
        $this->elasticSearchProjectInfoDataProvider = $elasticSearchClient;
    }

    /**
     * @return ElasticProjectDTO[]
     * @throws Exception
     */
    public function buildArray(): array
    {
        $returnArray = [];
        $projects = $this->elasticSearchProjectInfoDataProvider->getAllBonusProject();
        foreach ($projects as $project) {
            $returnArray[] = $this->build($project);
        }
        return $returnArray;
    }

    /**
     * @param array $elasticEntry
     * @return ElasticProjectDTO
     * @throws Exception
     */
    public function build(array $elasticEntry): ElasticProjectDTO
    {
        $object = new ElasticProjectDTO();
        $object->setKey($elasticEntry['_id']);
        $object->setName($elasticEntry['_source']['name']);
        $object->setStatus($elasticEntry['_source']['status']);
        $object->setEndDate(new DateTime($elasticEntry['_source']['endDate']));
        return $object;
    }
}