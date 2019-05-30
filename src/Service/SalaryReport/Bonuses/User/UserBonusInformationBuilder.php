<?php


namespace App\Service\SalaryReport\Bonuses\User;


use App\Entity\User;
use App\Service\ElasticSearchClient;
use App\Service\SalaryReport\Bonuses\Project\ElasticProjectDTO;
use App\Service\SalaryReport\Bonuses\Project\SalaryReportProjectDTOBuilder;

class UserBonusInformationBuilder
{
    /**
     * @var SalaryReportProjectDTOBuilder
     */
    private $salaryReportProjectDTOBuilder;
    /**
     * @var ElasticSearchClient
     */
    private $elasticSearchClient;

    public function __construct(
        SalaryReportProjectDTOBuilder $salaryReportProjectDTOBuilder,
        ElasticSearchClient $elasticSearchClient
    ) {
        $this->salaryReportProjectDTOBuilder = $salaryReportProjectDTOBuilder;
        $this->elasticSearchClient = $elasticSearchClient;
    }

    /**
     * @param User $user
     * @param ElasticProjectDTO[] $projects
     */
    public function buildArray(
        User $user,
        array $projects
    ) {
        $projectEstimates = $this->elasticSearchClient->getDeveloperProjectEstimate(['CAT-9136', 'CAT-8196'],
            'pavel.golomazov@onyx.com');
        foreach ($projects as $project) {
            $estimate = 0;
            if (!empty($projectEstimates[$project->getKey()])) {
                $estimate = $projectEstimates[$project->getKey()];
            }
            $this->salaryReportProjectDTOBuilder->build($project, $user, $estimate);
        }
//        $this->elasticSearchClient->getDeveloperProjectEstimate($this->getProjectKeys($projects), $user->getEmail());
    }

    /**
     * @param ElasticProjectDTO[] $projects
     * @return array
     */
    private function getProjectKeys(array $projects)
    {
        $keys = [];
        foreach ($projects as $project) {
            $key = $project->getKey();
            $keys[] = $key;
        }
        return $keys;
    }
}