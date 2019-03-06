<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 05.03.2019
 * Time: 18:11
 */

namespace App\Service\PhpDeveloperTest\TechnicalComponents;

use App\Entity\PhpDeveloperLevelTest;
use App\Entity\User;
use App\Service\ElasticSearchClient;
use App\Service\PhpDeveloperTest\PhpDeveloperTestBuilderException;

class TechnicalComponentBuilder
{
    /**
     * @var ElasticSearchClient
     */
    private $searchClient;

    /**
     * TechnicalComponentBuilder constructor.
     * @param ElasticSearchClient $searchClient
     */
    public function __construct(ElasticSearchClient $searchClient)
    {
        $this->searchClient = $searchClient;
    }

    /**
     * @param User $user
     * @param PhpDeveloperLevelTest $levelTest
     * @return array
     * @throws PhpDeveloperTestBuilderException
     */
    public function build(User $user, PhpDeveloperLevelTest $levelTest): array
    {
        $components = [];
        if (!empty($user->getEmail())) {
            foreach ($levelTest->getTechnicalComponents() as $technicalComponent) {
                if (empty($technicalComponent->getJiraName())) {
                    throw new PhpDeveloperTestBuilderException('No jira name for component');
                }
                $component = new TechnicalComponent();
                $component->setSpendHours($this->getSpendTime($technicalComponent->getJiraName(), $user->getEmail()));
                $component->setName($technicalComponent->getName());
                $component->setRequiredHours($technicalComponent->getRequiredHours());
                $components[] = $component;
            }
        } else {
            throw new PhpDeveloperTestBuilderException('User email is empty');
        }
        return $components;

    }

    private function getSpendTime(string $componentString, string $userName)
    {
        return $this->searchClient->getTimePerComponent($componentString, $userName);
    }
}
